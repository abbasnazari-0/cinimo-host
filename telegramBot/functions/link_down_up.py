import ftplib
import os
import utils
async def link_down_up(linkResult, message, path):
    await message.reply("لطفا منتظر بمانید")
    for link in linkResult:
        type = ""
        tag = utils.generate_string(20)
        if(utils.check_link( link ) == 'Image'):
            tag = tag + ".jpg"
            type = "image"
            await message.reply("عکس شناسایی شد \n عکس در حال آپلود است \n شما میتوانید تگ زیر را ثبت کنید \n \n \n <pre>"+ tag + "</pre>   ")
        elif(utils.check_link( link ) == 'Video'):
            tag = tag + ".mp4"
            type = "video"
            await message.reply("ویدیو شناسایی شد \n ویدیو در حال آپلود است \n شما میتوانید تگ زیر را ثبت کنید \n \n \n <pre>"+ tag + "</pre>   ")
        else:
            await message.reply("لینک شناسایی نشد")
            return

        # send new link to user
        # TODO Download from link and upload to server with tag name
        # download file from link
        file = ""
        if(type == "image"):
            file = path+ "/files/images/" + tag 
        else :
            file = path+ "/files/videos/" + tag 
        utils.download_file(link,file_name= file)
        
        file_ftp = open(file,'rb')                  # file to send
        
        ftpDirecotry = "";
        if(type == "image"):
            ftpDirecotry = "/images/"
        else :
            ftpDirecotry = "/videos/" 
        session = ftplib.FTP('dl.music.gamelevel.world','pz16041','5G3QB6g7')
        session.cwd("/domains/pz16041.parspack.net/public_html/cinimo" + ftpDirecotry)
        session.storbinary('STOR '+ tag, file_ftp)     # send the file
        file_ftp.close()                                    # close file and FTP
        session.quit()
        # remove file from local
        os.remove(file)