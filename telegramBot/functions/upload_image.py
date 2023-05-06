import utils
import ftplib
import os
import file_uploader
async def upload_image(message, path, client):
    tag =  utils.generate_string(20) + ".jpg"
    
    await message.reply("عکس شناسایی شد \n عکس در حال آپلود است \n شما میتوانید تگ زیر را ثبت کنید \n \n \n <pre>"+ tag + "</pre>   ", quote=True)

    
    
    photo = message.photo
    file_id = photo.file_id
    npath = path + f"/files/images/" + file_id + ".jpg"
    await client.download_media(message=photo, file_name=npath)
    
    # upload with s3 to arvan cloud
    file_uploader.uploadFile(tag, npath, "images")
    
    # remove file from local
    os.remove(npath)
