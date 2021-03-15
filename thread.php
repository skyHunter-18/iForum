<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="css/style.css"> -->

    <title>iForum - Threads</title>
</head>

<body>
    <?php include 'partials/_header.php'; ?>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php
    $id = $_GET['threadid'];
    $sql = "SELECT * FROM `threads` WHERE thread_id = $id";
    $result = mysqli_query($connection, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $thread_title = $row['thread_title'];
        $thread_desc = $row['thread_desc'];
    }
    ?>

    <?php
        $showAlert = false;
        $method =$_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            // Insert comments into database
            $comment = $_POST['comment'];
            $comment_user_id = $_POST["user_id"];
            $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_tstamp`, `comment_user_id`) VALUES 
            ('$comment', '$id', current_timestamp(), '$comment_user_id')";
            $result = mysqli_query($connection, $sql);
            $showAlert = true;

        }

        if($showAlert){
            echo '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Your comment has been posted successfully!!!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            ';
        }
    ?>

    <!-- Jumbotron Starts Here -->
    <div class="container my-3">
        <div class="jumbotron">
            <h1 class="display-4"><?php echo $thread_title; ?></h1>
            <p class="lead"><?php echo $thread_desc; ?></p>
            <hr class="my-4">
            <p>This is a peer to peer forum to share knowledge.

            <ul>
                <li>No Spam / Advertising / Self-promote in the forums.</li>
                <li>Do not post copyright-infringing material.</li>
                <li>Do not post “offensive” posts, links or images.</li>
                <li>Remain respectful of other members at all times.</li>
            </ul>

            </p>
            <p>Posted by: <b> 'User Name/ID'</b></p>
        </div>
    </div>
    <!-- Jumbotron Ends Here -->
        
    <!-- Add comments start here -->
    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
        echo '    
            <div class="container mb-4">
                <h1>Post a Comment</h1>
                <form action="' . $_SERVER["REQUEST_URI"] .'"  method="post">

                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Your comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="6" required></textarea>
                        <input type="hidden" name="user_id" value="'.$_SESSION['user_id'].'">
                    </div>
                    <button type="submit" class="btn btn-success left ">Post Comment</button>
                </form>
            </div>';
    }
    else {
        echo '
        <div class="container mb-4">
            <h1>Post a Comment</h1>
            <div class="text-center my-2">You are not logged in. Log in to post comment.</div>
        </div>
        ';
    }
    ?>
    <!-- Adding comments end here -->

    <div class="container" style="min-height: 180px">
        <h1>Comments</h1>

        <?php
                $id = $_GET['threadid'];
                $sql = "SELECT * FROM `comments` WHERE thread_id = $id";
                $result = mysqli_query($connection, $sql);
                $noResult = true;
                while ($row = mysqli_fetch_assoc($result)) {
                    $noResult = false;
                    $content = $row['comment_content'];
                    $commentID = $row['comment_id'];
                    $comment_time = $row['comment_tstamp'];
                    $comment_user_id = $row['comment_user_id'];
                    $sql_user = "SELECT user_email FROM `users` WHERE user_id = '$comment_user_id'";
                    $result_user = mysqli_query($connection, $sql_user);
                    $row_user = mysqli_fetch_assoc($result_user);

                    echo '
            <div class="media my-3">
                <img src="img/user-default.jpeg" width="40px" class="mr-3" alt="...">
                <div class="media-body">
                    <p class="font-weight-bold my-0">'. $row_user['user_email'] .' at '. $comment_time .'</p>
                    <p>' . $content . '</p>
                </div>
            </div>
            ';
                }
            // echo var_dump($noResult);
            if ($noResult) {
                echo '
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h1 class="display-4">No comments yet!!</h1>
                        <p class="lead"><b>Please be patient and wait ...</b></p>
                    </div>
                </div>
                ';
            }
                ?>

    </div>

    <?php include 'partials/_footer.php'; ?>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    -->
</body>

</html>