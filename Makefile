#
# XHProfUI MakeFile
#
# @author Tom Arnfeld <tarnfeld@me.com>
#
# Requirements:
#		- npm
#

#
# COMPOSER Update composer requirements
#
composer:
	@composer update

#
# BOOTSTRAP: Compile bootstrap
#
bootstrap:
	@echo "Changing to vendor/twitter/bootstrap";\
		cd vendor/twitter/bootstrap; \
		npm install; \
		make build;
	@echo "Copying assets"; \
		cp -r vendor/twitter/bootstrap/docs/assets ./assets
