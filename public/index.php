<?php

/**
 * This index help to show demo the system
 */

require_once '../bootstrap.php';

// need session to send message
if (!session_id()) session_start();

// route
\mj\routing::execute();