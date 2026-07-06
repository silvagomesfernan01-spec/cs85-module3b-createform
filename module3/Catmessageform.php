<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cat Message Form</title>
</head>
<body>
 
<?php

//Fernanda Gomes

// Running list of validation error messages
$errors = [];
 
// Default field values 
$FullName = "";
$Email    = "";
$Topic    = "";
$Message  = "";
 
// List of allowed topics, so we can also validate
// that the submitted value is one of the real options 
$topicOptions = [
    "General Cat Care",
    "Cat Adoption",
    "Cat Behavior",
    "Cat Health & Vet Questions",
    "Other Cat Topic"
];
 
// Only run validation logic if the form was submitted
if (isset($_POST['Submit'])) {
 
    // --- Sanitize every input with htmlspecialchars() ---
    $FullName = htmlspecialchars(trim($_POST['FullName'] ?? ""));
    $Email    = htmlspecialchars(trim($_POST['Email'] ?? ""));
    $Topic    = htmlspecialchars(trim($_POST['Topic'] ?? ""));
    $Message  = htmlspecialchars(trim($_POST['Message'] ?? ""));
 
    // --- Validate Full Name ---
    if ($FullName === "") {
        $errors[] = "Full Name is required.";
    }
 
    // --- Validate Email ---
    if ($Email === "") {
        $errors[] = "Email Address is required.";
    } elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        // FILTER_VALIDATE_EMAIL actually checks the format
        $errors[] = "Email Address is not valid.";
    }
 
    // --- Validate Topic ---
    if ($Topic === "") {
        $errors[] = "Please choose a Topic of Message.";
    } elseif (!in_array($Topic, $topicOptions)) {
        // Guards against someone submitting a value that wasn't one
        // of the dropdown's real options
        $errors[] = "Please choose a valid Topic of Message.";
    }
 
    // --- Validate Message word count (50-150 words) ---
    if ($Message === "") {
        $errors[] = "Message is required.";
    } else {
        // preg_split on whitespace gives an array of words;
        // array_filter removes any empty entries from extra spaces
        $wordCount = count(array_filter(preg_split('/\s+/', $Message)));
 
        if ($wordCount < 50) {
            $errors[] = "Message must be between 50 and 150 words. " .
                        "Currently: $wordCount word(s) — please add more detail.";
        } elseif ($wordCount > 150) {
            $errors[] = "Message must be between 50 and 150 words. " .
                        "Currently: $wordCount word(s) — please trim it down.";
        }
    }
}
 
?>

<h2 style="text-align:center;">🐱 Cat Message Form</h2>
 
<?php if (isset($_POST['Submit']) && empty($errors)): ?>
 
    <!-- Form passed all validation -->
    <p style="text-align:center;">
        Thank you, <?php echo $FullName; ?>! Your message about
        "<?php echo $Topic; ?>" has been received.
    </p>
 
<?php else: ?>
 
    <!-- Show validation errors, if any, above the form -->
    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <p>Please fix the following before submitting:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
 
    <!-- Self-processing form: action="" submits back to this same file -->
    <form name="catMessageForm" action="" method="post">
 
        <p>Full Name:<br>
            <input type="text" name="FullName" value="<?php echo $FullName; ?>" />
        </p>
 
        <p>Email Address:<br>
            <input type="text" name="Email" value="<?php echo $Email; ?>" />
        </p>
 
        <p>Topic of Message:<br>
            <select name="Topic">
                <option value="">-- Select a topic --</option>
                <?php foreach ($topicOptions as $option): ?>
                    <option value="<?php echo $option; ?>"
                        <?php if ($Topic === $option) echo "selected"; ?>>
                        <?php echo $option; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
 
        <p>Message (50–150 words):<br>
            <textarea name="Message" rows="8" cols="50"><?php echo $Message; ?></textarea>
        </p>
 
        <p>
            <input type="reset" value="Clear Form" />
            &nbsp;&nbsp;
            <input type="submit" name="Submit" value="Send Message" />
        </p>
 
    </form>
 
<?php endif; ?>
 
</body>
</html>
