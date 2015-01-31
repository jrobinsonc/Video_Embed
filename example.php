<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Video Embed</title>

    <style>
    .container {
        width: 860px;
        margin: 0 auto;
    }
    @media screen and (max-width: 900px) {
        .container {
            width: auto;
            margin: 0 10px;
        }
    }
    

    .video-embed {
        background-color: #000;
        position: relative; 
        padding-bottom: 56.25%; 
        height: 0; 
        overflow: hidden; 
        max-width: 100%; 
        height: auto; 
    } 
    .video-embed iframe, 
    .video-embed object, 
    .video-embed embed { 
        position: absolute; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
    }
    </style>
</head>
<body>
    
    <div class="container">

        <?php require 'video-embed.php'; ?>

        <div class="video-embed">
        <?php echo new Video_Embed('https://vimeo.com/94502406'); ?>
        </div>

    </div>

</body>
</html>