<?php

namespace App\Helpers;

use App\Models\Agt;
use App\Models\Article;
use App\Models\Student;
use App\Models\InvoiceReceipt;

use Illuminate\Http\Request;
use phpseclib\Crypt\RSA;




class Helpers
{

    public static function parseDouble($string)
    {
        return (float) str_replace(",", ".", str_replace(".", "", str_replace(" ", "", $string)));
    }

    public static function parseFloat($string)
    {
        return (float) str_replace(",", ".", str_replace(".", "", str_replace(" ", "", $string)));
    }

    public static function parseInt($string)
    {
        return (int) $string;
    }


    // retorna todos os meses do ano em português
    public static function meses()
    {
        $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        return $meses;
    }

    public static function saveFile(Request $request, $name, $source)
    {
        if ($request->hasFile($name)) {
            $fileNameExt = $request->file($name)->getClientOriginalName();
            $fileName = pathinfo($fileNameExt, PATHINFO_FILENAME);
            $fileExt = $request->file($name)->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $fileExt;
            $pathToStore = $request->file($name)->storeAs('public/' . $source, $fileNameToStore);
            return $fileNameToStore;
        }
        return 'null.png';
    }

    public static function uploadFile(Request $request, $name)
    {



        $request->validate([
            $name => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $fileName = time() . '.' . $request->$name->extension();
        $request->$name->move(public_path('upload/'), $fileName);
        return ($fileName ? $fileName : 'null.png');
    }

    public static function mecanografico($rubrica, $valor)
    {

        $mecanografico = '';
        if ($valor < 10) {
            $mecanografico = $rubrica . '000' . $valor;
        }
        if (($valor >= 10) && ($valor < 100)) {
            $mecanografico = $rubrica . '00' . $valor;
        }
        if (($valor >= 100) && ($valor < 1000)) {
            $mecanografico = $rubrica . '0' . $valor;
        }
        return $mecanografico;
    }

    public static function matriculaAGT()
    {
        //return 'Processado por programa validado 141/AGT/2019  SGESOFT';
        return 'Processado por programa validado 474/AGT/' . date('Y') . ' ' . config('app.name');
    }

    /* Assign invoice loaded */
    public static function assign($invoice, $documento, $company = null)
    {
        // return self::assinaturaAgt();


        $anoAtual = date('Y');
        $rsa = new RSA(); //Algoritimo RSA
        $keySaft = new KeySaft(); //


        $privatekey = $keySaft->getPrivateKey();
        // $publickey = $keySaft->getPublicKey();

        $rsa->loadKey($privatekey);
        // $rsa->loadKey($publickey);

        switch ($documento) {
            case 'proforma':
                if (Proforma::whereYear('created_at', $anoAtual)->count() > 0) // ifi is fierst invoice
                {
                    $last_hash = Proforma::orderBy('id', 'desc')->whereYear('created_at', $anoAtual)->first()->hash;
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->numero . ';' . number_format($invoice->subtotal + $invoice->imposto, 2, '.', '') . ';' . $last_hash;
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatena§Ãµes)
                    return base64_encode($signaturePlaintext);
                } else {
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->numero . ';' . number_format($invoice->subtotal + $invoice->imposto, 2, '.', '');
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
                    return base64_encode($signaturePlaintext);
                }

            case 'factura':
                if (Factura::whereYear('created_at', $anoAtual)->count() > 0) // ifi is fierst invoice
                {
                    $last_hash = Factura::orderBy('id', 'desc')->whereYear('created_at', $anoAtual)->first()->hash;
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->numero . ';' . number_format($invoice->subtotal + $invoice->imposto, 2, '.', '') . ';' . $last_hash;
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
                    return base64_encode($signaturePlaintext);
                } else {
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->numero . ';' . number_format($invoice->subtotal + $invoice->imposto, 2, '.', '');
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
                    return base64_encode($signaturePlaintext);
                }

            case 'factura-recibo':

                if (InvoiceReceipt::where("company_id", $company)->whereYear('created_at', $anoAtual)->count() > 0) // ifi is fierst invoice
                {


                    $last_hash = InvoiceReceipt::orderBy('id', 'desc')->whereYear('created_at', $anoAtual)->first()->hash;
                    $plaintext = date('Y-m-d', strtotime($invoice->date_of_issue)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->date_of_issue))) . ';' . $invoice->invoice_number . ';' . number_format($invoice->subtotal + $invoice->tax, 2, '.', '') . ';' . $last_hash;

                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)

                    return base64_encode($signaturePlaintext);
                } else {

                    $plaintext = date('Y-m-d', strtotime($invoice->date_of_issue)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->date_of_issue))) . ';' . $invoice->invoice_number . ';' . number_format($invoice->subtotal + $invoice->tax, 2, '.', '');
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)




                    return base64_encode($signaturePlaintext);
                }

            case 'recibo':
                if (Recibo::whereYear('created_at', $anoAtual)->count() > 0) // ifi is fierst invoice
                {
                    $last_hash = Recibo::orderBy('id', 'desc')->whereYear('created_at', $anoAtual)->first()->hash;
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->invoice_number . ';' . number_format($invoice->subtotal + $invoice->tax, 2, '.', '') . ';' . $last_hash;
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
                    return base64_encode($signaturePlaintext);
                } else {
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->numero . ';' . number_format($invoice->subtotal + $invoice->tax, 2, '.', '');
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
                    return base64_encode($signaturePlaintext);
                }

            case 'nota-credito':
                if (NotaCredito::whereYear('created_at', $anoAtual)->count() > 0) // ifi is fierst invoice
                {
                    $last_hash = NotaCredito::orderBy('id', 'desc')->whereYear('created_at', $anoAtual)->first()->hash;
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->numero . ';' . number_format($invoice->subtotal + $invoice->imposto, 2, '.', '') . ';' . $last_hash;
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
                    return base64_encode($signaturePlaintext);
                } else {
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->numero . ';' . number_format($invoice->subtotal + $invoice->imposto, 2, '.', '');
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
                    return base64_encode($signaturePlaintext);
                }

            case 'nota-debito':
                if (NotaDebito::whereYear('created_at', $anoAtual)->count() > 0) // ifi is fierst invoice
                {
                    $last_hash = NotaDebito::orderBy('id', 'desc')->whereYear('created_at', $anoAtual)->first()->hash;
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->numero . ';' . number_format($invoice->subtotal + $invoice->imposto, 2, '.', '') . ';' . $last_hash;
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
                    return base64_encode($signaturePlaintext);
                } else {
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->numero . ';' . number_format($invoice->subtotal + $invoice->imposto, 2, '.', '');
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
                    return base64_encode($signaturePlaintext);
                }


            case 'guia-transporte':
                if (GuiaTransporte::whereYear('created_at', $anoAtual)->count() > 0) // ifi is fierst invoice
                {
                    $last_hash = GuiaTransporte::orderBy('id', 'desc')->whereYear('created_at', $anoAtual)->first()->hash;
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->numero . ';' . number_format($invoice->subtotal + $invoice->imposto, 2, '.', '') . ';' . $last_hash;
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
                    return base64_encode($signaturePlaintext);
                } else {
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->numero . ';' . number_format($invoice->subtotal + $invoice->imposto, 2, '.', '');
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
                    return base64_encode($signaturePlaintext);
                }

            case 'guia-remessa':
                if (GuiaRemessa::whereYear('created_at', $anoAtual)->count() > 0) // ifi is fierst invoice
                {
                    $last_hash = GuiaRemessa::orderBy('id', 'desc')->whereYear('created_at', $anoAtual)->first()->hash;
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->numero . ';' . number_format($invoice->subtotal + $invoice->imposto, 2, '.', '') . ';' . $last_hash;
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
                    return base64_encode($signaturePlaintext);
                } else {
                    $plaintext = date('Y-m-d', strtotime($invoice->data)) . ';' . str_replace(" ", "T", date('Y-m-d H:i:s', strtotime($invoice->data))) . ';' . $invoice->numero . ';' . number_format($invoice->subtotal + $invoice->imposto, 2, '.', '');
                    $hash = 'sha1'; // Tipo de Hash
                    $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
                    //ASSINATURA
                    $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                    $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
                    return base64_encode($signaturePlaintext);
                }
        }
    }

    public static function setArtigoUsed($id)
    {
        $dados = Artigo::find($id);
        $dados->is_used = true;
        $dados->save();
    }

    public static function setClientesUsed($id)
    {
        $dados = Cliente::find($id);
        $dados->is_used = true;
        $dados->save();
    }

    public static function setFornecedorUsed($id)
    {
        $dados = Fornecedor::find($id);
        $dados->is_used = true;
        $dados->save();
    }

    public static function assinaturaAgt()
    {
        $array_chaves = str_split(Agt::all()->first()->private_key);
        $assinatura = '';
        $jump = 0;
        for ($i = 0; $i < 4; $i++) {
            $assinatura .= $array_chaves[$jump];
            $jump += 10;
        }
        return $assinatura;
    }


    public static function hashAgt($hash)
    {
        $partes = str_split($hash);
        return "$partes[0]$partes[10]$partes[20]$partes[30] ";
    }
}
