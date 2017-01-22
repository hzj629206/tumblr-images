tumblr-images
=============

Simple PHP Script run on Google Application Engine for ifttt, handling tumblr liked posts, return post image to Dropbox or Google Drive.

1. Register Google App Engine.
2. Create an app, when finished, enter https://console.cloud.google.com.
3. Switch to your app at right top corner, click Active Google Cloud Shell.
4. On the command line interface:

  ```
  git clone https://github.com/hzj629206/tumblr-images.git
  cd tumblr-images
  gcloud preview app deploy ./app.yaml --promote
  ```

5. Go to the URL that command prompted you, if there something like 'hello' shows up, you are good to go for configuring IFTTT.

finding for a way to download images urls whose inside txts?

1. get aria2c.exe from http://aria2.sourceforge.net/
2. make a text file named down_images.bat
3. paste codes below inside down_images.bat
    ```
    
    C:\Windows\system32\WindowsPowerShell\v1.0\powershell.exe -command "type *.txt | sort-object -unique | out-file all_images.txt -Encoding "UTF8""
    mkdir tumblr_images_download
    move all_images.txt ./tumblr_images_download/
    move aria2c.exe ./tumblr_images_download/
    cd tumblr_images_download/
    aria2c.exe -i all_images.txt
    
    ```
4. put txt files, down_images.bat and aria2c.exe inside a directory.
5. run the down_images.bat.
