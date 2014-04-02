<?php echo $this->Html->docType('html5'); ?>
<html>
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			<?php echo __d('frogban', 'FrogBan'); ?>:
			<?php echo $title_for_layout; ?>
		</title>
		<?php
			echo $this->Html->meta('icon', '/img/frogban.png');
			echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0, user-scalable=no'));
			echo $this->Html->css(array(
				'//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css',
				'//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css',
				'/cherry/css/core',
				'default'
			));
			echo $this->fetch('meta');
			echo $this->fetch('css');
		?>
		<!--[if lt IE 9]>
			<?php
				echo $this->Html->css(array(
					'//html5shiv.googlecode.com/svn/trunk/html5.js',
					'//raw.github.com/scottjehl/Respond/master/respond.min.js'
				));
			?>
		<![endif]-->
	</head>
	<body id="top" class="controller-<?php echo $this->request->params['controller']; ?> action-<?php echo $this->request->params['action']; ?>">
		<div class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<?php echo $this->Html->link($this->Html->image('frogban', array('width' => 20, 'height' => 20)) . ' FrogBan', '/', array('class' => 'navbar-brand', 'escape' => false)); ?>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<?php echo $this->Html->link('Cases <span class="caret"></span>', '#', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'escape' => false)); ?>
							<ul class="dropdown-menu">
								<li><?php echo $this->Html->link('Scrum', array('controller' => 'cases', 'action' => 'index')); ?></li>
								<?php if ($this->Session->check('Auth.fogbugz_url')): ?>
									<li><?php echo $this->Html->link('Open FogBugz', $this->Session->read('Auth.fogbugz_url'), array('target' => '_blank')); ?></li>
								<?php endif; ?>
							</ul>
						</li>
						<li class="dropdown">
							<?php echo $this->Html->link('API <span class="caret"></span>', '#', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'escape' => false)); ?>
							<ul class="dropdown-menu">
								<li><?php echo $this->Html->link('Dashboard', array('controller' => 'apis', 'action' => 'index')); ?></li>
								<li><?php echo $this->Html->link('Command', array('controller' => 'apis', 'action' => 'cmd')); ?></li>
							</ul>
						</li>
					</ul>
					<?php if ($this->Session->check('Auth.email')): ?>
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<?php echo $this->Html->link($this->Session->read('Auth.name') . ' <span class="caret"></span>', '#', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'escape' => false)); ?>
								<ul class="dropdown-menu">
									<li><?php echo $this->Html->link('Scrum', array('controller' => 'users', 'action' => 'view')); ?></li>
									<li class="divider"></li>
									<li><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?></li>
								</ul>
							</li>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="container">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<?php
			echo $this->Html->scriptBlock('var baseUrl = \'' . $this->Html->url('/') . '\';');
			echo $this->Html->script(array(
				'//code.jquery.com/jquery-1.11.0.min.js',
				'//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js',
				'/cherry/js/core',
				'default'
			));
			echo $this->fetch('script');
		?>
	</body>
</html>
