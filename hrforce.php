<?php
        ob_start();
        session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Welcome to HR Force - Your Human Management Platform</title>
        <!-- base library -->
        <link rel="stylesheet" type="text/css" href="css/hrforce-base.css" />
        <link rel="stylesheet" type="text/css" href="ext/resources/css/ext-all.css" />

        <!-- ExtJS extensions -->
        <link rel="stylesheet" href="ext/examples/ux/css/RowEditor.css" />
        <link rel="stylesheet" type="text/css" href="ext/examples/ux/css/Portal.css" />
        <link rel="stylesheet" type="text/css" href="ext/examples/ux/css/ColumnNodeUI.css" />

    </head>
    <body>
        <div id="loading-mask"></div>
        <div id="loading">
            <div class="loading-indicator">
		Loading HRForce...
            </div>
        </div>

        <!-- ExtJS library: base/adapter -->
        <script type="text/javascript" src="ext/adapter/ext/ext-base.js"></script>
        <!-- ExtJS library: all widgets -->
        <script type="text/javascript" src="ext/ext-all.js"></script>

        <!-- ExtJS extensions -->
        <script type="text/javascript" src="ext/examples/ux/statusbar/StatusBar.js"> </script>
        <script type="text/javascript" src="ext/examples/ux/Portal.js"></script>
        <script type="text/javascript" src="ext/examples/ux/PortalColumn.js"></script>
        <script type="text/javascript" src="ext/examples/ux/Portlet.js"></script>

        <script type="text/javascript" src="ext/examples/ux/RowEditor.js"></script>

        <script type="text/javascript" src="ext/examples/ux/treegrid/TreeGridSorter.js"></script>
        <script type="text/javascript" src="ext/examples/ux/treegrid/TreeGridColumnResizer.js"></script>
        <script type="text/javascript" src="ext/examples/ux/treegrid/TreeGridNodeUI.js"></script>
        <script type="text/javascript" src="ext/examples/ux/treegrid/TreeGridLoader.js"></script>

        <script type="text/javascript" src="ext/examples/ux/treegrid/TreeGridColumns.js"></script>
        <script type="text/javascript" src="ext/examples/ux/treegrid/TreeGrid.js"></script>

        <script type="text/javascript" src="ext/examples/ux/ColumnNodeUI.js"></script>

        <script type="text/javascript" src="js/hrforce.base.js"></script>

        <?php
        /*
          FOARTE rudimentar determinare de roluri it/hr/user
          Ar trebuii rescris
         */

        $userrole = 'user';
        if ($_SESSION['HRADMIN'] == 1) {
            $userrole = 'hradmin';
        }
        if ($_SESSION['ITADMIN'] == 1) {
            $userrole = 'itadmin';
        }

        //$userrole = $_GET['role'];
        if ($userrole == 'itadmin') {
            //daca e ITAdmin
        ?>
            <script type="text/javascript" src="views/administrator/js/hrforce.administrator.js"></script>
        <?php
        } elseif ($userrole == 'hradmin') {
            //daca e HRAdmin
        ?>
            <script type="text/javascript" src="views/hradmin/js/hrforce.hradmin.js"></script>
        <?php
        };
        ?>

    </body>
</html>