<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$user = JFactory::getUser();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Getting params from template
$params = $app->getTemplate(true)->params;

// template params
$fixedWidth = $params->get('fixedWidth');
$bootstrapVarJson = $params->get('bootstrapVariables');
$sassFilesJson = $params->get('sassFiles');
$fsrVars = $params->get('1srVariables');
$customLogo = $params->get('customLogo');
$showSiteName = $params->get('showSiteName');
$analyticsAccount = $params->get('analyticsAccount');
$mapsApiKey = $params->get('mapsApiKey');
$showCookieDeclineButton = $params->get('showCookieDeclineButton');

$bootstrapVars = json_decode($bootstrapVarJson, true);

$gridColumns = 12;

if(isset($bootstrapVars["grid-columns"])) {
  $gridColumns = $bootstrapVars["grid-columns"];
}


// Detecting Active Variables
$option = $app->input->getCmd('option', '');
$view = $app->input->getCmd('view', '');
$layout = $app->input->getCmd('layout', '');
$task = $app->input->getCmd('task', '');
$itemid = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');

if ($task == "edit" || $layout == "form") {
    $fullWidth = 1;
} else {
    $fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('jquery.framework');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/template.js');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/popper.min.js');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/bootstrap.min.js');

// Add Stylesheets

// require_once "scssphp-0.6.7/scss.inc.php";
//
// use Leafo\ScssPhp\Compiler;
//
// $scssDir = 'templates/' . $this->template . '/scss';
// // scss_server::serveFrom($scssDir);
//
// $scss = new Compiler();
// $scss->setImportPaths($scssDir);
// $scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');
//
// $scss->setVariables(array(
// 	'navBG' => '#f00',
// ));
//
// // add inline styles
// $doc->addStyleDeclaration($scss->compile('@import "1sr-bs4";'));

$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/template.css');

// $doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/scss/s.php/1sr-bs4.scss');

// import sass files by template configuration
foreach (json_decode($sassFilesJson, true) as $key => $sassFile) {
  $doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/scss/s.php/' . $sassFile);
}

// Adjusting content width
if ($this->countModules('sidebar-left') && $this->countModules('sidebar-right')) {
    $span = "col-md-".floor($gridColumns*0.5);
} elseif ($this->countModules('sidebar-left') && !$this->countModules('sidebar-right')) {
    $span = "col-md-".floor($gridColumns*0.75);
} elseif (!$this->countModules('sidebar-left') && $this->countModules('sidebar-right')) {
    $span = "col-md-".floor($gridColumns*0.75);
} else {
    $span = "col-md-".$gridColumns;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <jdoc:include type="head" />
        <?php if($params->get('favicon')) { ?>
            <link rel="shortcut icon" href="<?php echo JUri::root(true) . htmlspecialchars($params->get('favicon'), ENT_COMPAT, 'UTF-8'); ?>" />
        <?php } ?>
        <!--[if lt IE 9]>
                <script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <header class="navbar navbar-expand-sm navbar-static-top navbar-dark bg-primary yamm">
        		<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        			<span class="navbar-toggler-icon"></span>
        		</button>
            <?php if($showSiteName == "yes") : ?>
                <a class="navbar-brand" href="<?php echo $this->baseurl; ?>/" >
                    <?php echo $app->getCfg('sitename'); ?>
                </a>
		        <?php endif; ?>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <jdoc:include type="modules" name="navbar-1" style="none" />
                <jdoc:include type="modules" name="navbar-2" style="none" />
            </div>
        </header>
        <div class="body">
            <div class="content">
                <!--div class="jumbotron jumbotron-fluid bg-primary text-white">
                    <div class="container<?php echo ($fixedWidth == "yes") ? "" : "-fluid"; ?>">
                        <?php if(JURI::base() == JURI::current()) { ?>
                            <h1><?php echo $app->get('sitename'); ?></h1>
                                <?php if ($params->get('sitedescription')) { ?>
                                    <p class="lead">
                                        <?php echo htmlspecialchars($params->get('sitedescription'), ENT_COMPAT, 'UTF-8'); ?>
                                    </p>
                                <?php }?>
                        <?php } else {?>
                            <h1><?php echo $this->getTitle(); ?>
                        <?php } ?>
                    </div>
                </div-->

                <div class="container<?php echo ($fixedWidth == "yes") ? "" : "-fluid"; ?>">
                    <div class="row">
                        <div class="col-md-<?php echo $gridColumns; ?>">
                            <div id="logo">
                                <?php

                                    if($customLogo != "")
                                    {
                                    	$logoUrl = $this->baseurl."/".$customLogo;
                                    }
                                    else
                                    {
                                    	$logoUrl = $this->baseurl."/templates/".$this->template."/images/logo.jpg";
                                    }

                                ?>

                                <a href="<?php echo $this->baseurl; ?>/" title="<?php echo $app->getCfg('sitename'); ?>">
                                    <img class="img-fluid" src="<?php echo $logoUrl; ?>" title="<?php echo $app->getCfg('sitename'); ?>" alt="Logo <?php echo $app->getCfg('sitename'); ?>">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container<?php echo ($fixedWidth == "yes") ? "" : "-fluid"; ?>">
                  <jdoc:include type="modules" name="banner" style="xhtml" />
                  <?php if ($this->countModules('breadcrumbs')) : ?>
                      <div class="row">
                          <div class="col-sm-<?php echo $gridColumns; ?>">
                              <jdoc:include type="modules" name="breadcrumbs" style="xhtml" />
                          </div>
                      </div>
                  <?php endif; ?>
                    <div class="row">
                        <?php if ($this->countModules('sidebar-left')) : ?>
                            <div id="sidebar" class="col-md-<?php echo floor($gridColumns*0.25); ?>">
                                <div class="sidebar-nav">
                                    <jdoc:include type="modules" name="sidebar-left" style="xhtml" />
                                </div>
                            </div>
                        <?php endif; ?>
                        <main id="content" role="main" class="<?php echo $span; ?>">
                            <jdoc:include type="modules" name="position-<?php echo floor($gridColumns*0.25); ?>" style="xhtml" />
                            <jdoc:include type="message" />
                            <jdoc:include type="component" />
                            <jdoc:include type="modules" name="position-2" style="none" />
                        </main>
                        <?php if ($this->countModules('sidebar-right')) : ?>
                            <div id="aside" class="col-md-<?php echo floor($gridColumns*0.25); ?>">
                                <jdoc:include type="modules" name="sidebar-right" style="xhtml" />
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer bg-faded text-muted" role="contentinfo">
            <hr />
            <div class="container<?php echo ($fixedWidth == "yes") ? "" : "-fluid"; ?>">
                <div class="row">
                    <div class="col-sm-<?php echo floor(($gridColumns*1)/3); ?>"><p>
                            &copy; <?php echo date('Y'); ?> <?php echo $sitename; ?>
                        </p>
                    </div>
                    <div class="col-sm-<?php echo floor(($gridColumns*1)/3); ?> text-sm-center">
                        <jdoc:include type="modules" name="footer" style="none" />
                        <p></p>
                    </div>
                    <div class="col-sm-<?php echo floor(($gridColumns*1)/3); ?>">
                        <p class="text-sm-right">
                            <a href="#top" id="back-top">
                                <i class="fa fa-arrow-up"></i> <?php echo JText::_('TPL_BOOTSTRAP4_BACKTOTOP'); ?>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
        <jdoc:include type="modules" name="debug" style="none" />

        <?php if ($this->countModules('snippets')) : ?>
           <jdoc:include type="modules" name="snippets" />
       <?php endif; ?>
    </body>
</html>
