<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FichaAgendamento extends Controller
{
    public function printAgendamento($id)
    {
        
            //FORMATAR DATA
            $agendamento = Agendamento::find($id);
            Carbon::setLocale('pt-BR');
            $dataAtual = Carbon::now();
            
            


            //FORMATAR CPF
             $CPF_LENGTH = 11;
             $cnpj_cpf = preg_replace("/\D/", '', $agendamento->Cliente->cpf_cnpj);
            
            if (strlen($cnpj_cpf) === $CPF_LENGTH) {
                    $cpfCnpj = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
            }
            else {
                $cpfCnpj =  preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
            } 

            //FORMATAR TELEFONE
             $tel_1 = $agendamento->Cliente->telefone_1;
             $tel_2 = $agendamento->Cliente->telefone_2;
             $tel_1 = " (".substr($tel_1, 0, 2).") ".substr($tel_1, 2, 5)."-".substr($tel_1, 7, 11);
             $tel_2 = " (".substr($tel_2, 0, 2).") ".substr($tel_2, 2, 5)."-".substr($tel_2, 7, 11);

            return Pdf::loadView('pdf.agendamento.agendamento', compact(['agendamento',
                                                             'dataAtual',
                                                             'cpfCnpj',
                                                             'tel_1',
                                                             'tel_2']))->stream();

       // return view('pdf.contrato', compact(['locacao']));
       
    }
} 
       
