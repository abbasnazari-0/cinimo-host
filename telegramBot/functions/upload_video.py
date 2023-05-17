import utils
import os
import ftplib
import subprocess

def upload_video(message, path, client):
    tag = utils.generate_string(20) + ".mp4"
    
    message.reply("ویدیو شناسایی شد \n ویدیو در حال آپلود است \n شما میتوانید تگ زیر را ثبت کنید \n \n \n <pre>"+ tag + "</pre>   ", quote=True)

    video = message.video
    file_id = video.file_id
    npath = path + f"/files/videos/" + file_id + ".mp4"
    client.download_media(message=video, file_name=npath)
    

    # wateMark =  path + f"/files/" + "water.mp4"
    

    # command = 'ffmpeg -i '+wateMark+' -i '+npath+' -filter_complex "[0:v]scale=1366:768:force_original_aspect_ratio=1,pad=1366:768:-1:-1,setsar=1[v0]; [1:v]scale=1366:768:force_original_aspect_ratio=1,pad=1366:768:-1:-1,setsar=1[v1]; [v0][0:a][v1][1:a]concat=n=2:v=1:a=1[v][a]" -map "[v]" -map "[a]" -crf 18 -c:a aac -strict experimental ' + path + f"/files/videos/" + file_id + "-w.mp4" + '  -y ' 

    # try:
    #     subprocess.check_output(command, shell=True)
    #     print("FFmpeg command executed successfully.")
    #     npath = path + f"/files/videos/" + file_id + "-w.mp4" ;
    # except subprocess.CalledProcessError as e:
    #     print("Error executing FFmpeg command:", e)
    #     npath = path + f"/files/videos/" + file_id + ".mp4" ;


   
   
    
    
    file_ftp = open(npath,'rb')  
    
    ftpDirecotry = "/videos/"
    session = ftplib.FTP('dl.music.gamelevel.world','pz16041','5G3QB6g7')
    session.cwd("/domains/pz16041.parspack.net/public_html/cinimo" + ftpDirecotry)
    session.storbinary('STOR '+ tag, file_ftp)     # send the file
    file_ftp.close()                                    # close file and FTP
    session.quit()
    # remove file from local
    os.remove(npath)
    os.remove(npath.replace("-w.mp4" , ".mp4" ))
