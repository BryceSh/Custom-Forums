<?

if (isset($_SESSION['toast']) && $_SESSION['toast'] !== NULL) {
    
    $toastArray = $_SESSION['toast'];
  
    $toastType = $toastArray[0];
    $toastTitle = $toastArray[1];
    $toastBody = $toastArray[2];
    
    if ($toastType !== "" && $toastTitle !== "" && $toastBody !== "") {
  
        if ( $toastType == "success" ) {
            echo '<div id="us-toast" class="us-success">
              <div class="us-toast-header">
                <i class="fas fa-check-circle fa-lg me-2"></i>
                <strong>' . $toastTitle . '</strong>
                <div class="us-toast-right">
                  <button id="us-toast-close" type="button" class="btn-close btn-close-white" aria-label="Close"></button>
                </div>
              </div>
              <div class="us-toast-content">'. $toastBody .'</div>
            </div>';
        } elseif ( $toastType == "warn" ) {
            echo '<div id="us-toast" class="us-warn">
              <div class="us-toast-header">
                <i class="fas fa-exclamation-circle fa-lg me-2"></i>
                <strong>' . $toastTitle . '</strong>
                <div class="us-toast-right">
                  <button id="us-toast-close" type="button" class="btn-close btn-close-white" aria-label="Close"></button>
                </div>
              </div>
              <div class="us-toast-content">'. $toastBody .'</div>
            </div>';
        } elseif ( $toastType == "error" ) {
            echo '<div id="us-toast" class="us-error">
              <div class="us-toast-header">
                <i class="fas fa-times-circle fa-lg me-2"></i>
                <strong>' . $toastTitle . '</strong>
                <div class="us-toast-right">
                  <button id="us-toast-close" type="button" class="btn-close btn-close-white" aria-label="Close"></button>
                </div>
              </div>
              <div class="us-toast-content">'. $toastBody .'</div>
            </div>';
        } elseif ( $toastType == "info" ) {
            echo '<div id="us-toast" class="us-info">
              <div class="us-toast-header">
                <i class="fas fa-info-circle fa-lg me-2"></i>
                <strong>' . $toastTitle . '</strong>
                <div class="us-toast-right">
                  <button id="us-toast-close" type="button" class="btn-close btn-close-white" aria-label="Close"></button>
                </div>
              </div>
              <div class="us-toast-content">'. $toastBody .'</div>
            </div>';
        }
        
        $_SESSION['toast'] = array("", "", "");
        
    }
    
  }

?>