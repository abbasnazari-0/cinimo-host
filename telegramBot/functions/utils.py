import os
import random
import re
import string

import urllib3
def find_links(text):
    # تعریف الگوی ریجکس برای شناسایی لینک‌ها
    pattern = r'http[s]?://(?:[a-zA-Z]|[0-9]|[$-_@.&+]|[!*\(\),]|(?:%[0-9a-fA-F][0-9a-fA-F]))+'

    # استفاده از الگوی ریجکس برای شناسایی لینک‌ها در متن
    links = re.findall(pattern, text)

    # بازگرداندن لیست لینک‌های شناسایی شده
    return links

# detect video or photo type in link by regex format

def check_link(link):
    file_name = os.path.basename(link)
    
    if re.match(r'.*\.jpg|.*\.png|.*\.jpeg|.*\.bmp|.*.gif|.*.ico|.*.tif|.*.tiff', file_name):
        return 'Image'
    elif re.match(r'.*\.mp4|.*\.avi|.*\.3gp|.*\.flv|.*\.mov|.*\.wmv|.*\.mkv|.*\.webm|.*\.m4v', file_name):
        return 'Video'
    elif re.match(r'.*\.txt|.*\.pdf|.*\.doc|.*\.docx|.*\.xls|.*\.xlsx|.*\.ppt|.*\.pptx', file_name):
        return 'Document'
    elif re.match(r'.*\.mp3|.*\.wav|.*\.wma|.*\.aac|.*\.flac|.*\.m4a|.*\.ogg', file_name):
        return 'Audio'
    else:
        return 'Unknown'


# create function to generate string 
def generate_string(length):
    letters = string.ascii_lowercase
    result_str = ''.join(random.choice(letters) for i in range(length))
    return result_str



import urllib.request
import urllib3.request
def download_file(link, file_name):
    urllib.request.urlretrieve(link, file_name)


