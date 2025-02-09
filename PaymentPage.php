<?php
session_start();
$errors = [];

// Process form on submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate card number
    $card_number = $_POST['card_number'] ?? '';
    if (empty($card_number) || !preg_match('/^\d{16}$/', $card_number)) {
        $errors['card_number'] = 'Please enter a valid 16-digit card number.';
    }

    // Validate card holder name
    $card_holder = $_POST['card_holder'] ?? '';
    if (empty($card_holder) || !preg_match('/^[a-zA-Z\s]+$/', $card_holder)) {
        $errors['card_holder'] = 'Please enter the cardholder\'s full name.';
    }

    // Validate expiration month
    $exp_month = $_POST['exp_month'] ?? '';
    if (empty($exp_month) || !preg_match('/^(0[1-9]|1[0-2])$/', $exp_month)) {
        $errors['exp_month'] = 'Please select a valid expiration month.';
    }

    // Validate expiration year
    $exp_year = $_POST['exp_year'] ?? '';
    $current_year = date('Y');
    if (empty($exp_year) || !preg_match('/^\d{4}$/', $exp_year) || $exp_year < $current_year) {
        $errors['exp_year'] = 'Please select a valid expiration year.';
    }

    // Validate CVV
    $cvv = $_POST['cvv'] ?? '';
    if (empty($cvv) || !preg_match('/^\d{3,4}$/', $cvv)) {
        $errors['cvv'] = 'Please enter a valid CVV.';
    }

    // If no errors, process payment (simulated)
    if (empty($errors)) {
        $success = true; // Simulate payment success
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" href="PaymentPage.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
        }

        .success-message {
            color: green;
            font-size: 1.1em;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- NAV -->
<?php include("nav.php"); ?>
<!-- NAV -->

<div class="container">

    <div class="card-container">
        <div class="front">
            <div class="image">
                <img src="image/chip.png" alt="">
                <img src="image/visa.png" alt="">
            </div>
            <div class="card-number-box">################</div>
            <div class="flexbox">
                <div class="box">
                    <span>card holder</span>
                    <div class="card-holder-name">full name</div>
                </div>
                <div class="box">
                    <span>expires</span>
                    <div class="expiration">
                        <span class="exp-month">mm</span>
                        <span class="exp-year">yy</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="back">
            <div class="stripe"></div>
            <div class="box">
                <span>cvv</span>
                <div class="cvv-box"></div>
                <img src="image/visa.png" alt="">
            </div>
        </div>
    </div>

    <form action="" method="POST" id="payment-form">
        <div class="inputBox">
            <span>Card Number</span>
            <input type="text" name="card_number" maxlength="16" class="card-number-input" value="<?php echo htmlspecialchars($_POST['card_number'] ?? ''); ?>">
            <?php if (isset($errors['card_number'])): ?>
                <div class="error-message"><?php echo $errors['card_number']; ?></div>
            <?php endif; ?>
        </div>

        <div class="inputBox">
            <span>Card Holder</span>
            <input type="text" name="card_holder" class="card-holder-input" value="<?php echo htmlspecialchars($_POST['card_holder'] ?? ''); ?>">
            <?php if (isset($errors['card_holder'])): ?>
                <div class="error-message"><?php echo $errors['card_holder']; ?></div>
            <?php endif; ?>
        </div>

        <div class="flexbox">
            <div class="inputBox">
                <span>Expiration MM</span>
                <select name="exp_month" class="month-input">
                    <option value="" disabled selected>Month</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php echo ($_POST['exp_month'] ?? '') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : ''; ?>>
                            <?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <?php if (isset($errors['exp_month'])): ?>
                    <div class="error-message"><?php echo $errors['exp_month']; ?></div>
                <?php endif; ?>
            </div>

            <div class="inputBox">
                <span>Expiration YY</span>
                <select name="exp_year" class="year-input">
                    <option value="" disabled selected>Year</option>
                    <?php for ($i = date('Y'); $i <= date('Y') + 10; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($_POST['exp_year'] ?? '') == $i ? 'selected' : ''; ?>>
                            <?php echo $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <?php if (isset($errors['exp_year'])): ?>
                    <div class="error-message"><?php echo $errors['exp_year']; ?></div>
                <?php endif; ?>
            </div>

            <div class="inputBox">
                <span>CVV</span>
                <input type="text" name="cvv" maxlength="4" class="cvv-input" value="<?php echo htmlspecialchars($_POST['cvv'] ?? ''); ?>">
                <?php if (isset($errors['cvv'])): ?>
                    <div class="error-message"><?php echo $errors['cvv']; ?></div>
                <?php endif; ?>
            </div>
        </div>

        <input type="submit" value="Submit" class="submit-btn">
    </form>

</div>

<script>
<?php if (!empty($success)): ?>
    // Show SweetAlert for successful payment
    Swal.fire({
        icon: 'success',
        title: 'Payment Successful!',
        text: 'Your order will be on the way!',
        confirmButtonText: 'OK',
        confirmButtonColor: '#FF7F00', // Orange theme for the button
        iconColor: '#FF7F00', // Orange icon
    }).then(() => {
        window.location.href = 'index-4.php'; // Redirect to index-4.php
    });
<?php elseif (!empty($errors)): ?>
    // Focus on the first invalid input field
    document.querySelector('.error-message').scrollIntoView({ behavior: 'smooth' });
<?php endif; ?>



// Update visual card details dynamically
document.querySelector('.card-number-input').oninput = () => {
    document.querySelector('.card-number-box').innerText = document.querySelector('.card-number-input').value;
};
document.querySelector('.card-holder-input').oninput = () => {
    document.querySelector('.card-holder-name').innerText = document.querySelector('.card-holder-input').value;
};
document.querySelector('.month-input').oninput = () => {
    document.querySelector('.exp-month').innerText = document.querySelector('.month-input').value;
};
document.querySelector('.year-input').oninput = () => {
    document.querySelector('.exp-year').innerText = document.querySelector('.year-input').value;
};
document.querySelector('.cvv-input').onmouseenter = () => {
    document.querySelector('.front').style.transform = 'perspective(1000px) rotateY(-180deg)';
    document.querySelector('.back').style.transform = 'perspective(1000px) rotateY(0deg)';
};
document.querySelector('.cvv-input').onmouseleave = () => {
    document.querySelector('.front').style.transform = 'perspective(1000px) rotateY(0deg)';
    document.querySelector('.back').style.transform = 'perspective(1000px) rotateY(180deg)';
};
document.querySelector('.cvv-input').oninput = () => {
    document.querySelector('.cvv-box').innerText = document.querySelector('.cvv-input').value;
};
</script>

</body>
</html>
