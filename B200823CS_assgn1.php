<style>
    table,
    td,
    tr {
        border: 1px solid black;
    }
</style>

<?php
include("B200823CS_assgn1.html");
$first = '';
$last = '';
$roll = '';
$email = '';
if (isset($_POST['insert'])) {
    if (empty($_POST['First'])) {
        $firsterror = "First Name Field is Blank<br>";
        echo $firsterror;
        exit();
    } else {
        $first = $_POST['First'];
        $first = trim($first);
        $first = stripslashes($first);
        $first = htmlspecialchars($first);

        if (!preg_match("/^[a-zA-Z-' ]*$/", $first)) {
            $firsterror = "First Name should be in letters<br>";
            echo $firsterror;
            exit();
        }
    }

    if (empty($_POST['Last'])) {
        $lasterror = "Last Name Field is Blank<br>";
        echo $lasterror;
        exit();
    } else {
        $last = $_POST['Last'];
        $last = trim($last);
        $last = stripslashes($last);
        $last = htmlspecialchars($last);

        if (!preg_match("/^[a-zA-Z-' ]*$/", $last)) {
            $lasterror = "Last Name should be in letters<br>";
            echo $lasterror;
            exit();
        }
    }



    if (empty($_POST['Roll'])) {
        $rollerror = "Roll Number Field is Blank<br>";
        echo $rollerror;
        exit();
    } else {
        $roll = $_POST['Roll'];
        $roll = trim($roll);
        $roll = stripslashes($roll);
        $roll = htmlspecialchars($roll);

        $file = fopen("contact.csv", "a+");
        $check = fgetcsv($file);
        if ($check != NULL) {
            while (!feof($file)) {
                $check = fgetcsv($file);

                if ($check == NULL) {
                    break;
                }

                if ($roll == $check[2]) {
                    echo "Roll Number Already Present";
                    exit();
                }
            }
        }

        fclose($file);
    }

    if (empty($_POST['email'])) {
        $emailerror = "Email Field is Blank<br>";
        echo $emailerror;
        exit();
    } else {
        $email = $_POST['email'];
        $email = trim($email);
        $email = stripslashes($email);
        $email = htmlspecialchars($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailerror = "Invalid email format";
            echo $emailerror;
            exit();
        }
    }

    $fileo = fopen("contact.csv", "a+");

    $formd = array(
        'First' => $first,
        'Last' => $last,
        'Roll' => $roll,
        'email' => $email
    );

    fputcsv($fileo, $formd);
} else if (isset($_POST['display'])) {
    $file = fopen("contact.csv", "r");

    echo "<table>";

    while (!feof($file)) {
        $check = fgetcsv($file);

        if ($check == NULL) {
            break;
        }

        echo "<tr> <td>" . $check[0] . "</td> <td>" . $check[1] . "</td> <td>" . $check[2] . "</td> <td>" . $check[3] . "</td> </tr>";
    }

    echo "</table>";

    fclose($file);
} else if (isset($_POST['search'])) {
    $file = fopen("contact.csv", "r");
    $flag = 0;
    $roll = $_POST['Roll'];

    while (!feof($file)) {
        $check = fgetcsv($file);

        if ($check == NULL) {
            break;
        }

        if ($check[2] == $roll) {
            $first = $check[0];
            $last = $check[1];
            $roll = $check[2];
            $email = $check[3];
            $flag = 1;

            echo "$first $last<br>$roll<br>$email<br>";
            exit();
        }
    }

    if ($flag == 0) {
        echo "Search Unsuccessful";
    }

    fclose($file);
} else if (isset($_POST['update'])) {
    $first = $_POST['First'];
    $last = $_POST['Last'];
    $roll = $_POST['Roll'];
    $email = $_POST['email'];

    $file = fopen("contact.csv", "a+");
    $new = fopen("newcontact.csv", "a+");

    while (!feof($file)) {
        $check = fgetcsv($file);

        if ($check == NULL) {
            break;
        }

        if ($roll == $check[2]) {
            $formd = array(
                'First' => $first,
                'Last' => $last,
                'Roll' => $roll,
                'email' => $email
            );
            fputcsv($new, $formd);
        } else {
            $a = $check[0];
            $b = $check[1];
            $c = $check[2];
            $d = $check[3];

            $formd = array(
                'First' => $a,
                'Last' => $b,
                'Roll' => $c,
                'email' => $d
            );
            fputcsv($new, $formd);
        }
    }

    fclose($file);
    fclose($new);

    unlink("contact.csv");
    rename("newcontact.csv", "contact.csv");
} else if (isset($_POST['delete'])) {
    $roll = $_POST['Roll'];

    $file = fopen("contact.csv", "a+");
    $new = fopen("newcontact.csv", "a+");

    while (!feof($file)) {
        $check = fgetcsv($file);

        if ($check == NULL) {
            break;
        }

        if ($check[2] != $roll) {
            $a = $check[0];
            $b = $check[1];
            $c = $check[2];
            $d = $check[3];

            $formd = array(
                'First' => $a,
                'Last' => $b,
                'Roll' => $c,
                'email' => $d
            );
            fputcsv($new, $formd);
        }
    }

    fclose($file);
    fclose($new);

    unlink("contact.csv");
    rename("newcontact.csv", "contact.csv");
}
