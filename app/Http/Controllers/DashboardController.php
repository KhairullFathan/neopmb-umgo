<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Application;

class DashboardController extends Controller
{
    private function getApplicationQuery(Request &$request): Builder{
        $user = auth()->user();
        $query = Application::query();

        // Filter berdasarkan program studi yang boleh diakses user
        $query->whereHas('studyPrograms', function ($q) use ($user, $request) {
            if ($request->filled('study_program_code')) {
                $q->where('study_program_code', $request->study_program_code)
                    ->where('choice_number', 1);
            } else {
                $q->whereIn(
                    'study_program_code',
                    $user->studyPrograms()->select('study_programs.code')
                );
            }
        });
        return $query;
    }

    private function getApplicationDocumentQuery(Builder $query, String $documentStatus): Builder{
        switch ($documentStatus) {
            case 'complete':
                $query->whereHas('pmbRegistrationPath.pmbRegistrationDocument')
                    ->whereDoesntHave(
                        'pmbRegistrationPath.pmbRegistrationDocument',
                        function ($requiredDocument) {
                            $requiredDocument->whereDoesntHave(
                                'applicationDocuments',
                                function ($uploadedDocument) {
                                    $uploadedDocument
                                        ->whereColumn(
                                            'application_documents.application_id',
                                            'applications.id'
                                        )
                                        ->where('verification_status', 'approved');
                                }
                            );
                        }
                    );
                break;

            case 'incomplete':
                $query->whereHas(
                    'pmbRegistrationPath.pmbRegistrationDocument',
                    function ($requiredDocument) {
                        $requiredDocument->whereDoesntHave(
                            'applicationDocuments',
                            function ($uploadedDocument) {
                                $uploadedDocument
                                    ->whereColumn(
                                        'application_documents.application_id',
                                        'applications.id'
                                    )
                                    ->where('verification_status', 'approved');
                            }
                        );
                    }
                );
                break;
        }
        return $query;
    }

    private function getApplicationPaymentQuery(Builder $query, string $paymentType):Builder {
        $query->withSum([
            'payments as total_payment'=> function($q) use ($paymentType){
                $q->where('payment_type', $paymentType)
                    ->where('verification_status', 'approved');
            }
        ], 'amount');
        return $query;
    }

    private function getApplicationGraduateQuery(Builder $query): Builder{
        $query->whereHas('graduate');
        return $query;
    }

    public function index(Request &$request){
        $user = auth()->user();
        $application = $this->getApplicationQuery($request);
        $applicationDocumentComplete = $this->getApplicationDocumentQuery(clone $application, 'complete');
        $applicationDocumentIncomplete = $this->getApplicationDocumentQuery(clone $application, 'incomplete');

        $applicationPayment = $this->getApplicationPaymentQuery(clone $application, 'formulir')->get();

        $applicationGraduate = $this->getApplicationGraduateQuery(clone $application);

        return view('dashboard.index',[
            'title'=> 'Dashboard',
            'totalApplication'=> $application->count(),
            'totalApplicationDocumentComplete'=> $applicationDocumentComplete->count(),
            'totalApplicationDocumentIncomplete'=> $applicationDocumentIncomplete->count(),
            'totalApplicationPaymentApproved'=> $applicationPayment->sum('total_payment'),
            'totalApplicationGraduate'=> $applicationGraduate->count(),
        ]);
    }
}
