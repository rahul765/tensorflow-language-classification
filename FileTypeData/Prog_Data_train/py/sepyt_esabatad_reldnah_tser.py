# Copyright (C) 2005-2013 Splunk Inc. All Rights Reserved.
import logging
import splunk.admin as admin
from spp.config import EntityEndpoint, ConfigOption

logger = logging.getLogger('spp.dbx.dbtypes')
logger.setLevel(logging.DEBUG)


class DatabaseEntityEndpoint(EntityEndpoint):
    SUPPORTED_OPTIONS = (
        ("displayName", ConfigOption.MANDATORY), "typeClass", "jdbcDriverClass", "defaultPort", "connectionUrlFormat",
        "testQuery")
    CONFIG = 'database_types'

    def handleReload(self, confInfo):
        logger.debug("DBX database_types reload called")


admin.init(DatabaseEntityEndpoint, admin.CONTEXT_NONE)
