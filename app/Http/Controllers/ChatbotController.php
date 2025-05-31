<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function enviar(Request $request)
    {
        $userMessage = strtolower(trim($request->input('message')));
        $lang = $request->input('lang', 'es');

        if (!$userMessage) {
            return response()->json([
                'choices' => [
                    ['message' => ['content' => 'Por favor, escribe algo para poder ayudarte.']]
                ]
            ]);
        }
        $faqPath = resource_path("data/{$lang}.json");

        if (!file_exists($faqPath)) {
            return response()->json([
                'choices' => [
                    ['message' => ['content' => 'No se encontró el archivo de preguntas frecuentes.']]
                ]
            ]);
        }

        $faq = json_decode(file_get_contents($faqPath), true);

        foreach ($faq as $item) {
            foreach ($item['keywords'] as $keyword) {
                if (str_contains($userMessage, strtolower($keyword))) {
                    return response()->json([
                        'choices' => [
                            ['message' => ['content' => $item['respuesta']]]
                        ]
                    ]);
                }

                foreach (explode(' ', $userMessage) as $word) {
                    if (levenshtein($word, strtolower($keyword)) <= 1) {
                        return response()->json([
                            'choices' => [
                                ['message' => ['content' => $item['respuesta']]]
                            ]
                        ]);
                    }
                }
            }
        }

        $defaultResponse = $lang === 'en'
            ? 'Sorry, I don’t have information about that. Could you rephrase?'
            : 'Lo siento, no tengo información sobre eso. ¿Podrías reformular la pregunta?';

        return response()->json([
            'choices' => [
                ['message' => ['content' => $defaultResponse]]
            ]
        ]);
    }
}
