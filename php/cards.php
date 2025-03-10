<?php
require_once './etc/config.php';

try {
    $cards = CreditCard::findAll();
} 
catch (Exception $e) {
    echo $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/grid.css">
    <link rel="stylesheet" href="../css/styles.css">

    <title>Credit Cards</title>
</head>
<body>
    
    <div class="container">
        <div class="width-12">
            <h1>Credit Cards</h1>
        </div>
        <div class="width-8">
            <table>
                <thead>
                    <tr>
                        <th>Card Name</th>
                        <th>Card Type</th>
                        <th>Card Number</th>
                        <th>Expiry Date</th>
                        <th>CVV</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cards as $card) : ?>
                        <tr>
                            <td><?= $card->name ?></td>
                            <td><?= $card->number ?></td>
                            <td><?= $card->type ?></td>
                            <td><?= $card->exp_month . '/' . $card->exp_year ?></td>
                            <td><?= $card->cvv ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="width-4">
            <h2>Add Credit Card</h2>
            <form id="form-credit-card">
                <div class="form-group">
                    <label class="form-label">Card Type:</label>
                    <div class="radio-group">
                        <div>
                            <input type="radio" name="issuer" id="issuer-visa" value="visa">
                            <label for="issuer-visa">Visa</label>
                        </div>
                        <div>
                            <input type="radio" name="issuer" id="issuer-mcrd" value="mcrd">
                            <label for="issuer-mcrd">Mastercard</label>
                        </div>
                        <div>
                            <input type="radio" name="issuer" id="issuer-amex" value="amex">
                            <label for="issuer-amex">American Express</label>
                        </div>
                        <div>
                            <input type="radio" name="issuer" id="issuer-disc" value="disc">
                            <label for="issuer-disc">Discover</label>
                        </div>
                    </div>
                    <div class="error" id="error-issuer"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Name: </label>
                    <input type="text" name="name" value="" class="form-input" id="name">
                    <div class="error" id="error-name"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Card Number: </label>
                    <input type="text" name="number" value="" class="form-input" id="number">
                    <div class="error" id="error-number"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Expiry Month: </label>
                    <select name="month" id="month" class="form-select">
                        <option value="">Please choose the expiry month...</option>"
                        <option value="Jan">Jan</option>
                        <option value="Feb">Feb</option>
                        <option value="Mar">Mar</option>
                        <option value="Apr">Apr</option>
                        <option value="May">May</option>
                        <option value="Jun">Jun</option>
                        <option value="Jul">Jul</option>
                        <option value="Aug">Aug</option>
                        <option value="Sep">Sep</option>
                        <option value="Oct">Oct</option>
                        <option value="Nov">Nov</option>
                        <option value="Dec">Dec</option>
                    </select>
                    <div class="error" id="error-month"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Expiry Year: </label>
                    <select name="year" id="year" class="form-select">
                        <option value="">Please choose the expiry year...</option>"
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                    </select>
                    <div class="error" id="error-year"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">CVV: </label>
                    <input type="text" name="cvv" value="" class="form-input" id="cvv">
                    <div class="error" id="error-cvv"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Save card details: </label>
                    <input type="checkbox" name="save" value="Yes" id="save">Yes
                    <div class="error" id="error-save"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Accept terms and conditions: </label>
                    <input type="checkbox" name="accept" value="Yes" id="accept">Yes
                    <div class="error" id="error-accept"></div>
                </div>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
    
</body>
</html>