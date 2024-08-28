<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the form data
    $name = isset($_POST["name"]) ? htmlspecialchars(trim($_POST["name"])) : '';
    $email = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : '';
    $message = isset($_POST["message"]) ? htmlspecialchars(trim($_POST["message"])) : '';

    // Initialize error array
    $errors = [];

    // Validate inputs
    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($message)) {
        $errors[] = "Message is required.";
    }

    if (!empty($errors)) {
        // Output errors and stop further processing
        echo implode("<br>", $errors);
        exit();
    }

    // Email details
    $to = "your-email@example.com"; // Replace with your email address
    $subject = "Contact Form Submission from $name";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Construct the email body
    $emailBody = "Name: $name\n";
    $emailBody .= "Email: $email\n";
    $emailBody .= "Message:\n$message\n";

    // Prevent header injection
    if (preg_match('/[\r\n]/', $name) || preg_match('/[\r\n]/', $email) || preg_match('/[\r\n]/', $message)) {
        echo "Invalid input detected.";
        exit();
    }

    // Send the email
    if (mail($to, $subject, $emailBody, $headers)) {
        echo "Your message has been sent successfully.";
    } else {
        echo "There was an error sending your message. Please try again later.";
    }
} else {
    // Redirect to the home page if the form was not submitted via POST
    header("Location: /");
    exit();
}
?>
