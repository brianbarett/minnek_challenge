<?php
    $errors = [];

    class validationRules
    {
        public static function isEmpty($value) {
            if(empty($value)) {
                return true;
            } else {
                return false;
            }
        }

        public static function invalidEmail($value) {
            if(preg_match('/^[^\s@]+@[^\s@]+$/', $value) !== 1) {
                return true;
            } else {
                return false;
            }
        }

        public static function invalidPhone($value) {
            if(preg_match('/^\d{3}-\d{3}-\d{4}$/', $value) !== 1) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    function validateName() {
        if(validationRules::isEmpty($_REQUEST['name'])) {
            global $errors;
            $errors[] = 'Name is required';
        }
    }

    function validateEmail() {
        global $errors;
        if(validationRules::isEmpty($_REQUEST['email'])) {
            $errors[] = 'Email is required';
        }
        if(validationRules::invalidEmail($_REQUEST['email'])) {
            $errors[] = 'Invalid Email';
        }
    }

    function validatePhone() {
        global $errors;
        if(validationRules::isEmpty($_REQUEST['phone'])) {
            $errors[] = 'Phone is required';
        }
        if(validationRules::invalidPhone($_REQUEST['phone'])) {
            $errors[] = 'Invalid Phone';
        }
    }

    function validateMessage() {
        if(validationRules::isEmpty($_REQUEST['message'])) {
            global $errors;
            $errors[] = 'Message is required';
        }
    }

    function validateForm() {
        validateName();
        validateEmail();
        validatePhone();
        validateMessage();
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        validateForm();        
        if(empty($errors)) {
            $servername = 'localhost';
            $username = 'root';
            $password = '';
            $dbname = 'minnek_challenge';

            $db_connection = new mysqli($servername, $username, $password, $dbname);

            if ($db_connection->connect_error) {
                die("Connection failed: " . $db_connection->connect_error);
            }

            $statement = $db_connection->prepare("INSERT INTO minnek_form (`name`, email, phone, `message`) VALUES (?, ?, ?, ?)");
            $statement->bind_param("ssss", $_REQUEST['name'], $_REQUEST['email'], $_REQUEST['phone'], $_REQUEST['message']);

            if($statement->execute()) {
                echo "Form sent succesfuly";
            } else {
                die($statement->error);
            }

            $statement->close();
            $db_connection->close();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        #form > * {
            display: block;
            margin-bottom: 10px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div id="error-list">
        <?php
            foreach($errors as $error) {
                echo "<p class='error'>$error</p>";
            }
        ?>
    </div>

    <form id="form" method="POST" enctype="multipart/form-data" action="">
        <input type="text" name="name" placeholder="name" value="<?php if(isset($_REQUEST['name'])) { echo $_REQUEST['name']; } ?>">

        <input type="email" name="email" placeholder="email" value="<?php if(isset($_REQUEST['email'])) { echo $_REQUEST['email']; } ?>">

        <input type="tel" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="phone: ###-###-####" value="<?php if(isset($_REQUEST['phone'])) { echo $_REQUEST['phone']; } ?>">

        <textarea name="message" placeholder="message"><?php if(isset($_REQUEST['message'])) { echo $_REQUEST['message']; } ?></textarea>

        <input type="button" value="submit" onclick="validateForm()">
    </form>

    <script>
        const form = document.getElementById('form');

        const errors = [];
        const errorNodeContainer = document.getElementById('error-list');

        const validationRules =  {
            isEmpty(value) {
                if(!value || value.trim() === "") {
                    return true
                } else {
                    return false;
                }
            },

            invalidEmail(value) {
                if(!/^[^\s@]+@[^\s@]+$/g.test(value)) {
                    return true
                } else {
                    return false;
                }
            },

            invalidPhone(value) {
                if(!/^\d{3}-\d{3}-\d{4}$/g.test(value)) {
                    return true
                } else {
                    return false;
                }
            }
        }

        function validateName(name) {
            if(validationRules.isEmpty(name)) {
                errors.push('Name is required.');
            }
        }

        function validateEmail(email) {
            if(validationRules.isEmpty(email)) {
                errors.push('Email is required.');
            }
            if(validationRules.invalidEmail(email)) {
                errors.push('Invalid email.');
            }
        }

        function validatePhone(phone) {
            if(validationRules.isEmpty(phone)) {
                errors.push('Phone is required.');
            }
            if(validationRules.invalidPhone(phone)) {
                errors.push('Invalid phone format.');
            }
        }

        function validateMessage(message) {
            if(validationRules.isEmpty(message)) {
                errors.push('Message is required.');
            }
        }

        function validateForm() {
            errors.length = 0;
            errorNodeContainer.innerHTML = "";

            validateName(document.getElementsByName('name')[0].value);
            validateEmail(document.getElementsByName('email')[0].value);
            validatePhone(document.getElementsByName('phone')[0].value);
            validateMessage(document.getElementsByName('message')[0].value);

            if(!errors.length) {
                form.submit();
            } else {
                errors.forEach(error => {
                    const errorNode = document.createElement('p');
                    errorNode.classList.add('error')
                    errorNode.innerText = error;

                    errorNodeContainer.append(errorNode);
                });
            }
        }
    </script>
</body>
</html>