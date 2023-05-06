def uploadFile(fileName, path, folder):
    import boto3
    import logging
    from botocore.exceptions import ClientError

    # Configure logging
    logging.basicConfig(level=logging.INFO)

    try:
        s3_resource = boto3.resource(
            's3',
            endpoint_url='https://s3.ir-thr-at1.arvanstorage.com',
            aws_access_key_id='ef780d49-5c4c-4760-bb70-af06e75a8159',
            aws_secret_access_key='6128430f490b343dd3a93fa1a420e2cb23ce33f0'
        )

    except Exception as exc:
        logging.error(exc)
    else:
        try:
            bucket = s3_resource.Bucket('cinimo')
            file_path = path
            object_name = fileName

            with open(file_path, "rb") as file:
                bucket.put_object(
                    
                    ACL='public-read',
                    Body=file,
                    Key='cinimo/'+folder+'/{}'.format(object_name) 
                )
        except ClientError as e:
            logging.error(e)
