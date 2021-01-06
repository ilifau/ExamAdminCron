<?php
// Copyright (c) 2018 Institut fuer Lern-Innovation, Friedrich-Alexander-Universitaet Erlangen-Nuernberg, GPLv3, see LICENSE

include_once("./Services/Cron/classes/class.ilCronHookPlugin.php");

class ilExamAdminCronPlugin extends ilCronHookPlugin
{
	function getPluginName()
	{
		return "ExamAdminCron";
	}

	function getCronJobInstances()
	{
		return array($this->getCronJobInstance('exam_admin_cron'));
	}

	function getCronJobInstance($a_job_id)
	{
		$this->includeClass('class.ilExamAdminCronJob.php');
		return new ilExamAdminCronJob($this);
	}

	/**
	 * Do checks bofore activating the plugin
	 * @return bool
	 * @throws ilPluginException
	 */
	function beforeActivation()
	{
		if (!$this->checkAdminPluginActive()) {
			ilUtil::sendFailure($this->txt("message_admin_plugin_missing"), true);
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
	public function checkAdminPluginActive()
	{
		global $DIC;
		/** @var ilPluginAdmin $ilPluginAdmin */
		$ilPluginAdmin = $DIC['ilPluginAdmin'];

		return $ilPluginAdmin->isActive('Services', 'UIComponent', 'uihk', 'ExamAdmin');
	}

	/**
	 * Get the creator plugin object
	 * @return ilPlugin
	 */
	public function getAdminPlugin()
	{
		return ilPluginAdmin::getPluginObject('Services', 'UIComponent', 'uihk', 'ExamAdmin');
	}
}