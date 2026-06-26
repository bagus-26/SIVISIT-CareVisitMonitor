<?php
function getInitial($name) {
    if (empty($name)) return 'U';
    return strtoupper(substr($name, 0, 1));
}
