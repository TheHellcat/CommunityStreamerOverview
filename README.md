streameroverview
================

A Symfony project created on June 19, 2017, 5:46 pm.


**Prerequisites**
================

The usual fun, like ```composer install``` and the whole yadda yadaa.

**How to add an own configuration to the project**
================

1.  Add environment variable SYMFONY_ENV holding the name of your desired Symfony environment to your system or your apache vHost  
*(vHost example config line: ```SetEnv SYMFONY_ENV sampleenv```)*
2. Copy ```web/app_default.php``` according to your set environment name  
*(Example: ```app_sampleenv.php```)*
3. In you app_???.php, line #25, change the 'default' in  
```$kernel = new AppKernel('default', true);```  
to your own environment name.
4. Copy ```app/config/config_default.yml``` according to your env. name  
*(Example: copy to ```config_sampleenv.yml```)*
5. In your config file, line #3, change the name of the parameters file according to your env. name.  
*(Example: change ```resource: parameters_default.yml``` to ```resource: parameters_sampleenv.yml```)*
6. In your config file, in the section ```hellcat_twitch_api```, set your Twitch API details!
7. Copy the ```parameters_default.yml``` to the filename you specified in 5.
8. Edit the values in your parameters YML to fit your configurations
9. In ```app/AppKernel.php``` find the two IF lines similar to this:  
```if (in_array($this->getEnvironment(), [....], true))```  
and **add** your environment name to the array.
10. Clear cache *(Symfony cache!)*
11. **Create database structure (create tables)** by running on the console:  
```php bin\console -e=<your-env-name> doctrine:migrations:migrate```  
*(make sure to use your proper env. name for the -e parameter)*
