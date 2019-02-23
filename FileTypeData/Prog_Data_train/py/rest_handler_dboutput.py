# Copyright (C) 2005-2013 Splunk Inc. All Rights Reserved.
import logging
import splunk.admin as admin
from spp.config import EntityEndpoint, ConfigOption

logger = logging.getLogger('spp.dbx.dboutput')
logger.setLevel(logging.DEBUG)


class DatabaseOutputEntityEndpoint(EntityEndpoint):
    SUPPORTED_OPTIONS = (
        ConfigOption("database", mandatory=True),
        ConfigOption("table"),
        ConfigOption("mode"),
        ConfigOption("fields", multiValue=True, multiValueCount=100),
        ConfigOption("advanced", mandatory=True),
        ConfigOption("query")
    )

    CONFIG = "dboutput"

    def handleReload(self, confInfo):
        logger.debug("DBX dboutput reload called")


admin.init(DatabaseOutputEntityEndpoint, admin.CONTEXT_NONE)
