<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <title>iForum - Thread Lists</title>
</head>

<body>
    <?php include 'partials/_header.php'; ?>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `categories` WHERE cat_id = $id";
    $result = mysqli_query($connection, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $cat_Name = $row['cat_name'];
        $cat_Desc = $row['cat_desc'];
    }
    ?>

    <?php
        $showAlert = false;
        $method =$_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            // Insert threads into database
            $th_title = $_POST['thread_title'];
            $th_desc = $_POST['thread_desc'];
            $thread_user_id = $_POST["user_id"];
            $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `thread_tstamp`) VALUES 
                    ('$th_title', '$th_desc' , '$id', '$thread_user_id', current_timestamp())";
            $result = mysqli_query($connection, $sql);
            $showAlert = true;

        }
    if($showAlert){
        echo '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Your question has been posted successfully. Please wait for answers.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ';
        
        // <!-- Modal -->
        // echo '
        //     <div class="modal fade" id="threadInsertModal" tabindex="-1" aria-labelledby="threadInsertModalLabel" aria-hidden="true">
        //         <div class="modal-dialog">
        //             <div class="modal-content">
        //                 <div class="modal-header">
        //                     <h5 class="modal-title" id="threadInsertModalLabel">Success!!!</h5>
        //                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        //                         <span aria-hidden="true">&times;</span>
        //                     </button>
        //                 </div>
        //                 <div class="modal-body">
        //                     Your question has been posted successfully. Please wait for answers.
        //                 </div>
        //                 <div class="modal-footer">
        //                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        //                 </div>
        //             </div>
        //         </div>
        //     </div>
        // ';
    }
    ?>


    <!-- Category container (Jumbotron) Starts Here -->
    <div class="container my-3">
        <div class="jumbotron">
            <h1 class="display-4">Welcome, to <?php echo $cat_Name; ?> Forum!</h1>
            <p class="lead"><?php echo $cat_Desc; ?></p>
            <hr class="my-4">
            <p>This is a peer to peer forum to share knowledge.

            <ul>
                <li>No Spam / Advertising / Self-promote in the forums.</li>
                <li>Do not post copyright-infringing material.</li>
                <li>Do not post “offensive” posts, links or images.</li>
                <li>Remain respectful of other members at all times.</li>
            </ul>

            </p>
        </div>
    </div>
    <!-- Jumbotron Ends Here -->

    <?php
    // Add questions start here
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
        echo '
        <div class="container mb-4">
            <h1>Start A Discussion</h1>
            <form action="'. $_SERVER["REQUEST_URI"] .'" method="post">
                <div class="form-group">
                    <label for="exampleInputEmail1">Your Questions</label>
                    <input type="text" class="form-control" id="title" name="thread_title" aria-describedby="emailHelp" required>
                    <small id="emailHelp" class="form-text text-muted">Keep title short and crisp.</small>
                    <input type="hidden" name="user_id" value="'.$_SESSION['user_id'].'">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Describe your problem</label>
                    <textarea class="form-control" id="desc" name="thread_desc" rows="6" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
                <!-- <button type="submit" class="btn btn-success">Submit</button> -->
            </form>
        </div>';
    }
    else {
        echo '
        <div class="container mb-4">
            <h1>Start A Discussion</h1>
            <div class="text-center my-2">You are not logged in. Log in to start a discussion.</div>
        </div>
        ';
    }
    ?>
    <!-- // Adding questions end here -->

    <div class="container" style="min-height: 180px">
        <h1>Browse Questions</h1>

        <?php
        $id = $_GET['catid'];
        $sql = "SELECT * FROM `threads` WHERE thread_cat_id = $id";
        $result = mysqli_query($connection, $sql);
        $noResult = true;
        while ($row = mysqli_fetch_assoc($result)) {
            $noResult = false;
            $threadTitle = $row['thread_title'];
            $threadDesc = $row['thread_desc'];
            $threadID = $row['thread_id'];
            $threadTime = $row['thread_tstamp'];
            $thread_user_id = $row['thread_user_id'];
            $sql_user = "SELECT user_email FROM `users` WHERE user_id = '$thread_user_id'";
            $result_user = mysqli_query($connection, $sql_user);
            $row_user = mysqli_fetch_assoc($result_user);
            

            echo '
            <div class="media my-3">
                <img src="img/user-default.jpeg" width="40px" class="mr-3" alt="...">
                <div class="media-body">
                    
                    <h5 class="mt-0"><a href="thread.php?threadid=' . $threadID . '" class="text-dark">' . $threadTitle . '</a></h5>
                    <p>' . $threadDesc . '</p>
                </div>
                <p class="font-weight-bold my-0">'. $row_user['user_email'] .' at '. $threadTime .'</p>
                </div>
                ';
            }
            // echo var_dump($noResult);
            if ($noResult) {
                echo '
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h1 class="display-4">No questions yet!!</h1>
                        <p class="lead"><b>Be the first person to ask a question.</b></p>
                    </div>
                </div>
                ';
            }
            ?>


        <!-- the below media card is for testing purposes -->
        <!-- <div class="media my-3">
            <img src="img/user-default.jpeg" width="40px" class="mr-3" alt="...">
            <div class="media-body">
                <h5 class="mt-0">The question will show here</h5>
                <p>Will you do the same for me? It's time to face the music I'm no longer your muse. Heard it's
                    beautiful, be the judge and my girls gonna take a vote. I can feel a phoenix inside of me. Heaven is
                    jealous of our love, angels are crying from up above. Yeah, you take me to utopia.</p>
            </div>
        </div> -->


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