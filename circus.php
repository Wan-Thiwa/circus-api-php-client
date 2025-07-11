<?php
class CircusClient
{
    private string $apiToken;
    private string $writeUrl = "https://circusofthings.com/WriteValue";
    private string $readUrl = "https://circusofthings.com/ReadValue";

    public function __construct()
    {
        $this->apiToken = getenv('API_TOKEN');
        if (!$this->apiToken) {
            return [
                "success" => false,
                "message" => "API_TOKEN is not set in environment variables.",
                "data" => null
            ];
        }
    }

    public function writeValue(string $public_key, $value): array
    {
        try {
            $data = [
                "Key" => $public_key,
                "Token" => $this->apiToken,
                "Value" => $value
            ];

            $jsonData = json_encode($data);

            $ch = curl_init($this->writeUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Content-Length: " . strlen($jsonData)
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($response === false) {
                return [
                    "success" => false,
                    "message" => "cURL error: $error",
                    "data" => null
                ];
            }

            if ($httpCode !== 200) {
                return [
                    "success" => false,
                    "message" => "Error: Request failed with status code $httpCode",
                    "data" => null
                ];
            }

            return [
                "success" => true,
                "message" => "อัปเดตข้อมูลสำเร็จ",
                "data" => json_decode($response, true)
            ];
        } catch (Exception $e) {
            return [
                "success" => false,
                "message" => "Exception: " . $e->getMessage(),
                "data" => null
            ];
        }
    }

    public function readValues(string $public_key): array
    {
        try {
            $urlWithQuery = $this->readUrl . "?" . http_build_query([
                "Key" => $public_key,
                "Token" => $this->apiToken
            ]);

            $ch = curl_init($urlWithQuery);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response_json = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($response_json === false) {
                return [
                    "success" => false,
                    "message" => "cURL error: $error",
                    "data" => null
                ];
            }

            if ($httpCode !== 200) {
                return [
                    "success" => false,
                    "message" => "Error: Request failed with status code $httpCode",
                    "data" => null
                ];
            }

            $response = json_decode($response_json, true);

            return [
                "success" => true,
                "message" => "รับข้อมูลสำเร็จ",
                "data" => $response
            ];
        } catch (Exception $e) {
            return [
                "success" => false,
                "message" => "Exception: " . $e->getMessage(),
                "data" => null
            ];
        }
    }
}
