import utils
import ftplib
import os
async def upload_image(message, path, client):
    tag =  utils.generate_string(20) + ".jpg"
    
    await message.reply("عکس شناسایی شد \n عکس در حال آپلود است \n شما میتوانید تگ زیر را ثبت کنید \n \n \n <pre>"+ tag + "</pre>   ", quote=True)

    photo = message.photo
    file_id = photo.file_id
    npath = path + f"/files/images/" + file_id + ".jpg"
    await client.download_media(message=photo, file_name=npath)
    
    file_ftp = open(npath,'rb')  
    
    ftpDirecotry = "/images/"
    session = ftplib.FTP('dl.music.gamelevel.world','pz16041','5G3QB6g7')
    session.cwd("/domains/pz16041.parspack.net/public_html/cinimo" + ftpDirecotry)
    session.storbinary('STOR '+ tag, file_ftp)     # send the file
    file_ftp.close()                                    # close file and FTP
    session.quit()
    # remove file from local
    os.remove(npath)