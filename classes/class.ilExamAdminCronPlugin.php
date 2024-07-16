<?php
// Copyright (c) 2018 Institut fuer Lern-Innovation, Friedrich-Alexander-Universitaet Erlangen-Nuernberg, GPLv3, see LICENSE

include_once("./Services/Cron/classes/class.ilCronHookPlugin.php");

class ilExamAdminCronPlugin extends ilCronHookPlugin
{
	function getPluginName(): string
	{
		return "ExamAdminCron";
	}

	function getCronJobInstances(): array
	{
		return array($this->getCronJobInstance('exam_admin_cron'));
	}

	function getCronJobInstance($a_job_id): ilCronJob
	{
		return new ilExamAdminCronJob($this);
	}

	/**
	 * Do checks bofore activating the plugin
	 * @return bool
	 * @throws ilPluginException
	 */
	function beforeActivation(): bool
	{
		global $DIC;
		
		if (!$this->checkAdminPluginActive()) {
			$DIC->ui()->mainTemplate()->setOnScreenMessage('failure', $this->txt("message_admin_plugin_missing"), true);
			// this does not show the message
			// throw new ilPluginException($this->txt("message_creator_plugin_missing"));
			return false;
		}

		return parent::beforeActivation();
	}

	/**
	 * Check if the player plugin is active
	 * @return bool
	 */
	public function checkAdminPluginActive(): bool
	{
		global $DIC;

		/** @var ilComponentFactory $factory */
		$factory = $DIC["component.factory"];
	
		/** @var ilPlugin $plugin */
		foreach ($factory->getActivePluginsInSlot('uihk') as $plugin) {
			if ($plugin->getPluginName() == 'ExamAdmin') {
				return $plugin->isActive();
			}
		}
		return false;	
	}


	/**
	 * Get the creator plugin object
	 * @return ilPlugin
	 */
	public function getAdminPlugin(): ilPlugin
	{
		global $DIC;

        /** @var ilComponentFactory $factory */
        $factory = $DIC["component.factory"];

		return $factory->getPlugin('examad');
	}
}