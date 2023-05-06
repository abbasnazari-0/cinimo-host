import utils
import os
import ftplib
import file_uploader
async def upload_video(message, path, client):
    tag = utils.generate_string(20) + ".mp4"
    
    await message.reply("ویدیو شناسایی شد \n ویدیو در حال آپلود است \n شما میتوانید تگ زیر را ثبت کنید \n \n \n <pre>"+ tag + "</pre>   ", quote=True)

    video = message.video
    file_id = video.file_id
    npath = path + f"/files/videos/" + file_id + ".mp4"
    await client.download_media(message=video, file_name=npath)
    
    # upload with s3 to arvan cloud
    file_uploader.uploadFile(tag, npath, "videos")

    # remove file from local
    os.remove(npath)
