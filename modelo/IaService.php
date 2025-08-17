<?php

class IaService {
    private $geminiApiKey;
    private $geminiEndpoint;

    public function __construct() {
        $this->geminiApiKey = "AIzaSyDMQH688h_TNmarvq40RmaqkH_errJ3Las";
        $this->geminiEndpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent";
    }

    private function chamarGemini($prompt) {
        $url = $this->geminiEndpoint . "?key=" . $this->geminiApiKey;

        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ]
        ];

        $options = [
            "http" => [
                "header"  => "Content-Type: application/json\r\n",
                "method"  => "POST",
                "content" => json_encode($data),
                "ignore_errors" => true
            ]
        ];

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            throw new Exception("Erro ao chamar API Gemini.");
        }

        return json_decode($result, true);
    }

    public function gerarResposta($pergunta) {
    $prompt = "
Você é um assistente especializado em sustentabilidade ecológica.

Regras:
- Sempre forneça uma resposta definitiva, trazendo números aproximados ou médias conhecidas, mesmo que não sejam exatos.
- Quando o valor exato variar (ex.: emissões de carro, banho, avião, país), explique brevemente que depende de fatores (modelo, combustível, duração, região, etc.), mas apresente sempre uma estimativa média numérica.
- Responda perguntas sobre meio ambiente, mudanças climáticas, emissões de carbono, energia renovável, reciclagem e consumo consciente.
- Explique de forma clara, prática e acessível, sem respostas longas.
- Use exemplos rápidos e valores médios para ajudar o usuário a entender.
- Não invente dados absurdos; use aproximações coerentes com valores geralmente aceitos.
- Evite termos técnicos excessivos, fale de forma simples e direta.
- Seja objetivo, prestativo e amigável.

Pergunta: \"$pergunta\"

Resposta:
";


        $resposta = $this->chamarGemini($prompt);
        $texto = $resposta["candidates"][0]["content"]["parts"][0]["text"] ?? "";

        if (empty($texto)) {
            throw new Exception("Não foi possível gerar uma resposta.");
        }

        return trim($texto);
    }
}
