<?php
session_start();
include_once("db_connect.php");
include("dictionary.php");

function getName($db, $uid)
{
    $sql = "SELECT name FROM titan1 WHERE id = $uid";

    $res = $db->query($sql);

    if ($res != FALSE && $res->rowCount() == 1) {
        $nameRow = $res->fetch();

        return $nameRow['name'];
    } else {
        return "";
    }
}

function showLoginForm()
{
?>
    <form name="login" method="POST" action="quizMaker.php?op=login" style="margin: 10px;">
        <input type="text" name="uid" placeholder="User Id" size="4" />
        <input type="submit" value="Log in" class="btn btn-primary" />
    </form>

<?php
}

function showLogoutForm()
{
?>
    <form name="login" method="POST" action="quizMaker.php?op=logout" style="margin: 10px;">
        <input type="submit" value="Log out" class="btn btn-primary" />
    </form>

<?php
}


function generateInformation()
{
?>
    <div>Some information regarding this page: </div>
    <p>It is a simple vocabulary quiz webpage for Spanish vocabulary. </p>

<?php
}

function quizOptionForm($db, $uid)
{
?>
    <h2>Generate quizzes</h2>
    <div class="container">
        <form name="genQ" action="quizMaker.php?op=quiz" method="POST">
            <div class="form-group">
                <label for="M">Number of multiple-choice questions:</label>
                <input type="number" class="form-control" name="M" min="1" max="10" required>
            </div>
            <div class="form-group">
                <label for="T">Number of true-false questions:</label>
                <input type="number" class="form-control" name="T" min="1" max="10" required>
            </div>
            <div class="form-group">
                <label for="F">Number of fill-in-the-blanks questions:</label>
                <input type="number" class="form-control" name="F" min="1" max="10" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
<?php
}

function genQuiz($db, $uid, $options, $dict)
{
    $nM = $options['M'];
    $nT = $options['T'];
    $nF = $options['F'];

    //insert quiz to database 
    $sql = "INSERT INTO quiz(uid, qdate, score, maxScore) VALUES ($uid, NOW(), 0, $nM+$nT+$nF)";
    $res = $db->query($sql);

    if ($res == FALSE) {
        header("refresh:2;url=quizMaker.php?op=start");
        printf("<H3>Failed to generate quizzes</H3>\n");
        return;
    }

    $probNum = 1;
    $qid = $db->lastInsertId();

    echo "<form action='quizMaker.php?op=quizSolution' method='POST'>\n";

    for ($i = 0; $i < $nM; $i++) {
        $wid = genMultipleChoice($dict, $probNum);
        ++$probNum;

        //insert and display multiple choices questions
        $sqlM = "INSERT INTO problem(qid, uid, wid, ptype) VALUES ($qid, $uid, $wid, 'M')";
        $res = $db->query($sqlM);
    }

    for ($i = 0; $i < $nT; $i++) {
        $wid = genTrueFalse($dict, $probNum);
        ++$probNum;

        $sqlT = "INSERT INTO problem(qid, uid, wid, ptype) VALUES ($qid, $uid, $wid, 'T')";
        $res = $db->query($sqlT);
    }

    for ($i = 0; $i < $nF; $i++) {
        $wid = genFIB($dict, $probNum);
        ++$probNum;

        $sqlF = "INSERT INTO problem(qid, uid, wid, ptype) VALUES ($qid, $uid, $wid, 'F')";
        $res = $db->query($sqlF);
    }

    echo "<input type='submit' value='Submit' class='btn btn-primary' style='margin-left : 10px;'/>\n";
    echo "<form>\n";
}

function scoreQuiz($db, $uid, $quizData)
{
    echo "<div style='text-align: center;'>\n";
    echo "<h2>Quiz Results</h2>\n";

    $num = count($quizData) / 2;
    $score = 0;

    for ($i = 0; $i < $num; $i++) {
        $pno = $i + 1;

        $correctAns = $quizData['correctAnswer' . $pno];
        $ans = $quizData['answer' . $pno];

        echo "<div style='border: 1px solid #ddd; border-radius: 5px; margin: 10px; padding: 10px; background-color: #f7f7f7;'>\n";
        echo "<p style='font-weight: bold; margin-bottom: 10px;'>Q$pno. Your answer: $ans</p>\n";

        if ($correctAns == $ans) {
            echo "<p style='color: green;'>Correct Answer: $correctAns</p>\n";
            ++$score;
        } else {
            echo "<p style='color: red;'>Correct Answer: $correctAns</p>\n";
        }

        echo "</div>\n";
    }

    echo "<h3>Your Score: $score out of $num</h3>\n";
    echo "</div>\n";

    $pid = $db->lastInsertId();
    echo "$pid";
    // $sql = "UPDATE quiz SET score = $score WHERE qid IN (SELECT qid FROM problem WHERE uid = $uid AND pid = $pid)";
    // $res = $db->query($sql);

    // if ($res == FALSE) {
    //     header("refresh:2;url=quizMaker.php?op=start");
    //     printf("<H3>Failed to get score</H3>\n");
    // }
}


function randomEntry($dict)
{
    $randIdx = array_rand($dict);
    return $dict[$randIdx];
}

function genMultipleChoice($dict, $pno)
{
    $questions = [];

    for ($i = 0; $i < 4; $i++) {
        $questions[$i] = randomEntry($dict);
    }

    $markQuestion = $questions[random_int(0, 3)];
    $correctAns = $markQuestion['en'];
    $markSWord = $markQuestion['es'];

    echo "<div class='question-container'>\n";
    echo "<p class='question-text'>Q$pno. What is the correct English for '$markSWord'?</p>\n";

    //hidden
    echo "<input type='hidden' name='correctAnswer$pno' value='$correctAns'>\n";

    foreach ($questions as $question) {
        $answer = $question['en'];

        echo "<div class='form-check'>\n";
        echo "<input class='form-check-input' type='radio' name='answer$pno' value='$answer'>\n";
        echo "<label class='form-check-label'>$answer</label>\n";
        echo "</div>\n";
    }

    echo "</div>\n";

    return $markQuestion['wid'];
}

function genTrueFalse($dict, $pno)
{
    $questions = [];

    for ($i = 0; $i < 2; $i++) {
        $questions[$i] = randomEntry($dict);
    }

    $markQuestion = $questions[random_int(0, 1)];
    $randomQuestion = $questions[random_int(0, 1)];

    $correctAns = $markQuestion['en'];
    $randAns = $randomQuestion['en'];
    $markSWord = $markQuestion['es'];

    echo "<div class='question-container'>\n";
    echo "<p class='question-text'>Q$pno. Is '$randAns' the correct English for '$markSWord'?</p>\n";

    //hidden
    if ($correctAns == $randAns) {
        echo "<input type='hidden' name='correctAnswer$pno' value='True' />\n";
    }
    else {
        echo "<input type='hidden' name='correctAnswer$pno' value='False' />\n";
    }

    echo "<div class='form-check'>\n";
    echo "<input class='form-check-input' type='radio' name='answer$pno' value='True' />\n";
    echo "<label class='form-check-label'>True</label>\n";
    echo "</div>\n";

    echo "<div class='form-check'>\n";
    echo "<input class='form-check-input' type='radio' name='answer$pno' value='False' />\n";
    echo "<label class='form-check-label'>False</label>\n";
    echo "</div>\n";

    echo "</div>\n";

    return $markQuestion['wid'];
}

function genFIB($dict, $pno)
{
    $markQuestion = randomEntry($dict);
    $correctAns = $markQuestion['en'];
    $markSWord = $markQuestion['es'];

    echo "<div class='question-container'>\n";
    echo "<p class='question-text'>Q$pno. For the Spanish word '$markSWord', the equivalent English word is <span class='blank-word'>___</span>.</p>\n";

    //hidden
    echo "<input type='hidden' name='correctAnswer$pno' value='$correctAns'>\n";

    echo "<input class='form-control' type='text' name='answer$pno' placeholder='Your Answer'>\n";
    echo "</div>\n";

    return $markQuestion['wid'];
}


?>