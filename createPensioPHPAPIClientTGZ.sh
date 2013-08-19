#!/bin/bash

tar czf /tmp/PensioClientPHPAPI.tgz $(find . -type f | grep -v .svn | grep -v .idea | grep -v .settings | grep -v "./external/" | grep -v "./test/" | grep -v .buildpath | grep -v .project | grep -v build.xml | grep -v createPensioPHPAPIClientTGZ.sh |  sed 's/\.\///')
