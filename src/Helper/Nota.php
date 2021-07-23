<?php

namespace Template\Helper;

trait Nota
{
    
    private $curl;
    private $apikey = "3a99669a48cad39031692931a90b8db93c99c63310f02afe3444fd186f78805222dd5e95";

    public function pending($data)
    {
        $outputType = "json";

        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "https://bling.com.br/Api/v2/notasfiscais/$outputType/?filters=situacao[1]&apikey=$this->apikey",
            CURLOPT_RETURNTRANSFER => TRUE,
        ));

        $response = curl_exec($this->curl);
        $response = json_decode($response);

        curl_close($this->curl);

        if(is_null($response)){
            sleep(5);
            $this->pending($data);
            exit();
        }

        $response = array_filter($response->retorno->notasfiscais, function ($elem) use ($data){
            $nota = explode('-', $elem->notafiscal->numeroPedidoLoja);
            return $nota[1] == $data->id;
        });
        $response = current($response);

        if (!empty($response) && !is_null($response) && !is_null($response->notafiscal->chaveAcesso)) {
            $this->publishNota($response->notafiscal, 2);
        }
        return $this->sendNota($response->notafiscal);
    }

    public function nota($data)
    {

        $xml = $this->xml($data);
        $posts = array (
            "apikey" => "3a99669a48cad39031692931a90b8db93c99c63310f02afe3444fd186f78805222dd5e95",
            "xml" => rawurlencode($xml)
        );

        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => 'https://bling.com.br/Api/v2/notafiscal/json/',
            CURLOPT_POST => count($posts),
            CURLOPT_POSTFIELDS => $posts,
            CURLOPT_RETURNTRANSFER => TRUE,
        ));

        $response = curl_exec($this->curl);
        $response = json_decode($response);

        curl_close($this->curl);

        if(is_null($response)){
            sleep(5);
            $this->nota($data);
            exit();
        }

        if (isset($response->retorno->erros) && $response->retorno->erros[0]->erro->cod == 55) {
            return $this->pending($data);
        } else {
            return $this->sendNota($response->retorno->notasfiscais[0]->notaFiscal);
        }
    }

    public function xml($data)
    {
        // Receberá todos os dados do XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';

        // A raiz do meu documento XML
        $xml .= '<pedido>';
        $xml .= "<numero_loja>". rand() . '-' . $data->id ."</numero_loja>";

        // Dados do Cliente
        $xml .= '<cliente>';

        $xml .= "<nome>$data->compradorPrimeiroNome $data->compradorSobrenome</nome>";
        $xml .= "<tipoPessoa>F</tipoPessoa>";
        $xml .= "<cpf_cnpj>$data->compradorDocumento</cpf_cnpj>";
        $xml .= "<endereco>$data->enderecoEntregaRua</endereco>";
        $xml .= "<numero>$data->enderecoEntregaNumero</numero>";
        $xml .= "<complemento>$data->enderecoEntregaComentario</complemento>";
        $xml .= "<bairro>$data->enderecoEntregaBairro</bairro>";
        $xml .= "<cep>$data->enderecoEntregaCep</cep>";
        $xml .= "<cidade>$data->enderecoEntregaCidade</cidade>";
        $xml .= "<uf>$data->enderecoEntregaEstado</uf>";
        $xml .= "<fone>($data->compradorCodigoAreaTelefone) $data->compradorTelefone</fone>";
        $xml .= "<email>$data->compradorEmail</email>";

        $xml .= '</cliente>';

        // Dados de Transporte
        $xml .= '<transporte>';

        $xml .= "<especie>Volumes</especie>";

        $xml .= "<dados_etiqueta>";
        $xml .= "<endereco>$data->enderecoEntregaRua</endereco>";
        $xml .= "<numero>$data->enderecoEntregaNumero</numero>";
        $xml .= "<complemento>$data->enderecoEntregaComentario</complemento>";
        $xml .= "<bairro>$data->enderecoEntregaBairro</bairro>";
        $xml .= "<cep>$data->enderecoEntregaCep</cep>";
        $xml .= "<municipio>$data->enderecoEntregaCidade</municipio>";
        $xml .= "<uf>$data->enderecoEntregaEstado</uf>";
        $xml .= '</dados_etiqueta>';

        $xml .= "<volumes>";
        $xml .= "<volume>";
        $xml .= "<servico>$data->tipoEntrega</servico>";
        $xml .= "<codigoRastreamento>$data->numeroRastreio</codigoRastreamento>";
        $xml .= '</volume>';
        $xml .= '</volumes>';

        $xml .= '</transporte>';

        // Dados dos Itens
        $xml .= "<itens>";
        foreach ($data->Item as $item) {
            $xml .= "<item>";
            $xml .= "<codigo>$item->codigoProdutoItem</codigo>";
            $xml .= "<descricao>$item->tituloProdutoItem</descricao>";
            $xml .= "<un>un</un>";
            $xml .= "<qtde>$item->quantidadeItem</qtde>";
            $xml .= "<vlr_unit>$item->precoUnitarioItem</vlr_unit>";
            $xml .= "<tipo>P</tipo>";
            $xml .= "<origem>0</origem>";
            $xml .= '</item>';
        }
        $xml .= '</itens>';

        $xml .= "<vlr_frete>$data->tarifaEnvio</vlr_frete>";
        $xml .= "<vlr_desconto>$data->valorDesconto</vlr_desconto>";

        $xml .= '</pedido>';

        // Escreve o arquivo
        $fp = fopen('nota-fiscal.xml', 'w+');
        fwrite($fp, $xml);
        fclose($fp);
        return file_get_contents(URL_BASE . 'nota-fiscal.xml');
    }

    public function getNota($data)
    {
        $documentNumber = $data->numero;
        $documentSerie = $data->serie;
        $outputType = "json";

        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "https://bling.com.br/Api/v2/notafiscal/$documentNumber/$documentSerie/$outputType&apikey=$this->apikey",
            CURLOPT_RETURNTRANSFER => TRUE,
        ));

        $response = curl_exec($this->curl);
        $response = json_decode($response);

        curl_close($this->curl);

        if(is_null($response)){
            sleep(5);
            $this->getNota($data);
            exit();
        }

        return $this->publishNota($response->retorno->notasfiscais[0]->notafiscal, 2);
    }

    public function publishNota($data, $type)
    {
        $this->curl = curl_init();

        $id = explode('-', $data->numeroPedidoLoja);
        $id = $id[1];

        $nota = [
            'idPedido' => $id,
            'processo' => $type, 
            'chaveNota' => $data->chaveAcesso,
            'numeroNota' => $data->numero,
            'serieNota' => $data->serie,
            'dataEmissaoNota' => $data->dataEmissao,
            'urlDanfe' => $data->linkDanfe,
            'xmlNota' => null
        ];

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => 'http://api.ideris.com.br/NotaFiscal',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($nota),
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($this->curl);
        $response = json_decode($response);

        curl_close($this->curl);

        if(is_null($response)){
            sleep(5);
            $this->publishNota($data, $type);
            exit();
        }

        if (is_string($response)) {
            $this->publishNota($data, 1);
            exit();
        }

        return $this->requestNota(json_decode(json_encode(['id' => $id])));
    }

    public function sendNota($data)
    {

        $posts = array(
            "apikey"    => "3a99669a48cad39031692931a90b8db93c99c63310f02afe3444fd186f78805222dd5e95",
            "number"    => $data->numero,
            "serie"     => $data->serie,
            "sendEmail" => "false"
        );

        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "https://bling.com.br/Api/v2/notafiscal/json/",
            CURLOPT_POST => count($posts),
            CURLOPT_POSTFIELDS => $posts,
            CURLOPT_RETURNTRANSFER => TRUE,
        ));

        $response = curl_exec($this->curl);
        $response = json_decode($response);

        curl_close($this->curl);

        if(is_null($response)){
            sleep(5);
            $this->sendNota($data);
            exit();
        }

        return $this->getNota($data);
    }

    public function requestNota($data)
    {
        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => "http://api.ideris.com.br/NotaFiscal?idPedido=$data->id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: {$this->token}",
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($this->curl);
        $response = json_decode($response);

        curl_close($this->curl);

        if(is_null($response)){
            sleep(5);
            $this->requestNota($data);
            exit();
        }

        if($response->paging->count === 0 && isset($data->compradorPrimeiroNome)) {
            $this->nota($data);
        }

        if ($response->paging->count > 0) {
            return $response;
        } 
    }
    
}