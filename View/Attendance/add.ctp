<div class="attendees form">
<?php echo $this->Form->create('Attendee'); ?>
	<fieldset>
		<legend><?php echo __('Add Attendee'); ?></legend>
	<?php
		echo $this->Form->dateTime('from', array('type' => 'datetime', 'timeFormat' => 24, 'interval' => 30, 'oclock' => true));
		echo $this->Form->dateTime('to', array('type' => 'datetime', 'timeFormat' => 24, 'interval' => 30));
		echo $this->Form->input('display_email', array('after' => ' Only logged in users will be able to see it.'));
		echo $this->Form->input('comment');
		echo $this->Form->input('event_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Back'), array('action' => 'index')); ?></li>
	</ul>
</div>
