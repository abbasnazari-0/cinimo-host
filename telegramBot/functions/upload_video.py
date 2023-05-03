import utils
import os
import ftplib
async def upload_video(message, path, client):
    tag = utils.generate_string(20) + ".mp4"
    
    await message.reply("ویدیو شناسایی شد \n ویدیو در حال آپلود است \n شما میتوانید تگ زیر را ثبت کنید \n \n \n <pre>"+ tag + "</pre>   ", quote=True)

    video = message.video
    file_id = video.file_id
    npath = path + f"/files/videos/" + file_id + ".mp4"
    await client.download_media(message=video, file_name=npath)
    
    file_ftp = open(npath,'rb')  
    
    ftpDirecotry = "/videos/"
    session = ftplib.FTP('dl.music.gamelevel.world','pz16041','5G3QB6g7')
    session.cwd("/domains/pz16041.parspack.net/public_html/cinimo" + ftpDirecotry)
    session.storbinary('STOR '+ tag, file_ftp)     # send the file
    file_ftp.close()                                    # close file and FTP
    session.quit()
    # remove file from local
    os.remove(npath)
