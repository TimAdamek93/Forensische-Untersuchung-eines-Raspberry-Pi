<?php

// check
if ( !defined( 'MASTERANDSERVANTS' ) ) die();

// functions
function makeHeader() {
global $config;
?>
<!DOCTYPE html>
<html lang="de" dir="ltr" class="h-100">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="refresh" content="30">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $config[ "title" ]; ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
      body {
         font-family: SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;
         padding-top: 5rem;
      }
      .container {
        width: auto;
        max-width: 800px;
      }
      .footer {
        background-color: #f5f5f5;
      }
    </style>
  </head>
  <body class="d-flex flex-column h-100">
    <main role="main" class="flex-shrink-0">
      <div class="container">
        <h1><?php echo $config[ "title" ]; ?></h1>
<?php
}

function getSshPorts() {
  $cmdline = "ss -4 -H -n -o state listening sport \\> 45677 sport \\< 49152 | awk '{ print $4; }' | cut -d ':' -f 2 | sort -u";
  ob_start();
  passthru( $cmdline );
  $cmdoutput = ob_get_contents();
  ob_end_clean();
  return $cmdoutput;
}

function makeButton( $servant, $command ) {
?>
                <form action="./master.php" method="post" id="button-<?php echo $servant[ "id" ]; ?>">
                  <input type="hidden" name="id" value="<?php echo $servant[ "id" ]; ?>">
                  <?php if( $servant[ "id" ] == "1234567890" ) { ?>
                    <input type="hidden" name="command" value="<?php echo ( $servant[ "command" ] == "close" ) ? "open" : "close"; ?>">
                    <button type="submit" class="btn btn-sm <?php echo ( $servant[ "command" ] == "close" ) ? "btn-success" : "btn-danger"; ?>" type="submit" data-toggle="tooltip" data-html="true" title="<i>Switch for testing.</i>">
                      <?php echo ( $servant[ "command" ] == "close" ) ? "open" : "close"; ?>
                    </button>
                  <?php } else { ?>
                    <input type="hidden" name="command" value="<?php echo $command; ?>">
                    <button type="submit" class="btn btn-sm <?php echo ( $command == "open" ) ? "btn-success" : "btn-danger"; ?>" type="submit" data-toggle="tooltip" data-html="true" title="ssh -p <b><?php echo $servant[ "port" ]; ?></b> pi@localhost">
                      <?php echo $command; ?>
                    </button>
                  <?php } ?>
                </form>
<?php
}

function makeFooter() {
?>
      </div>
    </main>
    <footer class="footer mt-auto py-3">
      <div class="container">
        <span class="text-muted">(c) 2020 by Tim A. and Patrick N., quick and dirty layouted with <a href="https://getbootstrap.com/">Bootstrap</a>.</span>
      </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
      window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove();
        });
      }, 3000);
    </script>
  </body>
</html>
<?php
}

// for debugging only
function showArray( $array ) {
  echo "<pre>";
  print_r( $array );
  echo "</pre>";
}

?>
