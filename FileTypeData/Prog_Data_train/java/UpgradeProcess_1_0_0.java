package com.liferay.timesheet.hook.upgrade;

import com.liferay.portal.kernel.upgrade.UpgradeProcess;
import com.liferay.timesheet.hook.upgrade.v1_0_0.UpgradeTask;
import com.liferay.timesheet.model.Task;
import com.liferay.timesheet.service.TaskLocalServiceUtil;

import java.util.List;

/**
 * @author Istvan Sajtos
 */
public class UpgradeProcess_1_0_0 extends UpgradeProcess {

	@Override
	protected void doUpgrade() throws Exception {
		if (!isFirstRun()) {
			return;
		}

		upgrade(UpgradeTask.class);
	}

	protected boolean isFirstRun() throws Exception {
		List<Task> task = TaskLocalServiceUtil.getTasks(0, 1);

		if (!task.isEmpty()) {
			return false;
		}

		return true;
	}

}
