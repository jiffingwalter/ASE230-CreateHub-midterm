<?php
function display_error($context,$origin){
    echo "<h5 style='background-color: indianred; color: white; padding: 4px'>
            ERROR at ".$origin."</br>
            ".$context."
        </h5>";
}