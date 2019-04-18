@echo off
title Create component
echo Namespace: msnet
echo Enter name for new component:
set /p name=
echo %name%
xcopy "%CD%\..\local\components\msnet\component" "%CD%\..\local\components\msnet\%name%" /s /e /i
pause
