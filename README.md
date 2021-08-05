## منصة مشاركة فيديوهات  
## Video Sharing Platform

### Running the App =>

`composer install`

`npm install`
 
Create a database and add it to your .env file

Add this line to your .env file

`FILESYSTEM_DRIVER=public`

Currently pusher.com service is used for sending notifications, so you need to register there and then
update the values to correspond with the configuration below:

`PUSHER_APP_ID=`

`PUSHER_APP_KEY=` 

`PUSHER_APP_SECRET=`

After that execute this command

`php artisan storage:link`

Run the db migration command with the seed option

`php artisan migrate:fresh --seed`

#### For seeding purposes you need to =>
Add images to your public folder with these names (image1, image2, image3, image4)
Also you need to add some videos to your public folder with these names (240p-video.mp4, 360p-video.mp4, 480p-video.mp4)

Now run your app using the serve command

`php artisan serve`

And start the queue using this command

`php artisan queue:work`
