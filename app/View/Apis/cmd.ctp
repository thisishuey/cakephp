<?php if (!empty($cmdResponse)): ?>
	<p>
		<div class="text-right">
			<?php echo $this->Html->link('JSON', '/' . $this->request->url . '.json', array('target' => '_blank', 'class' => 'btn btn-success btn-xs')); ?>
			<?php echo $this->Html->link('XML', '/' . $this->request->url . '.xml', array('target' => '_blank', 'class' => 'btn btn-success btn-xs')); ?>
		</div>
	</p>
	<?php $configureDebug = Configure::read('debug'); ?>
	<?php Configure::write('debug', 2); ?>
	<?php debug($cmdResponse, null, false); ?>
	<?php Configure::write('debug', $configureDebug); ?>
<?php endif; ?>
