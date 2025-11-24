<?php
class mailService {
    private string $fromEmail;
    private string $teamEmail;

    public function  __construct(
        string $fromEmail = "no-reply@biletecluj.ro",
        string $teamEmail = "biletecluj@gmail.com"
    ){
        $this->fromEmail = $fromEmail;
        $this->teamEmail = $teamEmail;
    }

    public function sendHtmlMail(string $to, string $subject, string $htmlBody, ?string $from = null): bool
    {
        $fromAddress = $from ?? $this->fromEmail;

        $headers = "From: {$fromAddress}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        return mail($to, $subject, $htmlBody, $headers);
    }

    public function sendContactMessage(string $name, string $email, string $message): bool
    {
        $subject = "Mesaj nou din formularul de contact";

        $body = "
        <html lang='ro'>
            <head><title>Mesaj nou de la utilizator</title></head>
            <body>
                <p><strong>Nume:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Mesaj:</strong> {$message}</p>
            </body>
        </html>
        ";

        return $this->sendHtmlMail($this->teamEmail, $subject, $body, $email);
    }

    public function sendOrderConfirmation(string $toEmail, array $orderData): bool
    {
        $subject = "Confirmare comandă bilete - Cluj Events";

        $itemsHtml = '';
        foreach ($orderData['items'] as $item){
            $itemsHtml .= "<li>"
                .htmlspecialchars($item['name']). " - "
                . (int)$item['quantity'] . ' x '
                . number_format($item['price_at_purchase'], 2) . " RON "
                . "</li>";
        }

        $body = "
            <html lang='ro'>
                <body>
                    <p>Bună, " . htmlspecialchars($orderData['user_name'] ?? '') . "!</p>
                    <p>Îți mulțumim pentru comanda plasată pe <strong>Cluj Events</strong>.</p>
                    <p><strong>Comanda #{$orderData['order_id']}</strong> din {$orderData['order_date']} are următoarele bilete:</p>
                    <ul>{$itemsHtml}</ul>
                    <p><strong>Total:</strong> " . number_format($orderData['total_amount'], 2) . " RON</p>
                    <p>Ne vedem la eveniment! 🎭🎶</p>
                </body>
            </html>";

        return $this->sendHtmlMail($toEmail, $subject, $body);
    }
}
?>