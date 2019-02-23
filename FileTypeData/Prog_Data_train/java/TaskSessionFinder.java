/**
 * Copyright (c) 2000-2013 Liferay, Inc. All rights reserved.
 *
 * The contents of this file are subject to the terms of the Liferay Enterprise
 * Subscription License ("License"). You may not use this file except in
 * compliance with the License. You can obtain a copy of the License by
 * contacting Liferay, Inc. See the License for the specific language governing
 * permissions and limitations under the License, including but not limited to
 * distribution rights of the Software.
 *
 *
 *
 */

package com.liferay.timesheet.service.persistence;

/**
 * @author Istvan Sajtos, Zsolt Szabo
 */
public interface TaskSessionFinder {
	public java.util.List<com.liferay.timesheet.model.TaskSession> findByC_U_I(
		long companyId, long userId, java.util.Date date1, java.util.Date date2);

	public java.util.List<com.liferay.timesheet.model.TaskSession> findByU_T_I(
		long userId, long taskId, java.util.Date date1, java.util.Date date2);
}