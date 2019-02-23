package com.liferay.timesheet.bean.view;

import com.liferay.faces.util.logging.Logger;
import com.liferay.faces.util.logging.LoggerFactory;
import com.liferay.timesheet.util.TimeSheetConstants;
import com.liferay.timesheet.util.TimeSheetUtil;

import java.util.Date;

import javax.faces.bean.ManagedBean;
import javax.faces.bean.ViewScoped;

@ManagedBean
@ViewScoped
public class MyStatisticsViewBean extends AbstractStatisticsBean {

	public MyStatisticsViewBean() {
		super();
	}

	public void init() {
		try {
			setCurrentDate(TimeSheetUtil.getTodayWithoutTime());
			setCurrentUser(TimeSheetUtil.getCurrentUser());

			setEndDate(new Date());
			setStartDate(new Date(0));
			setDateNumber(TimeSheetConstants.DATE_DAY);

			setCurrentLevel(2);
		} catch (Exception e) {
			logger.error("Error", e);
		}
	}

	private static final long serialVersionUID = -3812731893224070452L;
	private static final Logger logger =
		LoggerFactory.getLogger(MyStatisticsViewBean.class);

}