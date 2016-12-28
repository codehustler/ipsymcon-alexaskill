import logging
import urllib2
import json

########################################
# Enter here your IP Symcon Connect ID #
########################################
myips = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"

logger = logging.getLogger()
logger.setLevel(logging.INFO)

def lambda_handler(event, context):
    access_token = event['payload']['accessToken']

    if event['header']['namespace'] == 'Alexa.ConnectedHome.Discovery':
        return handleDiscovery(context, event)

    elif event['header']['namespace'] == 'Alexa.ConnectedHome.Control':
        return handleControl(context, event)

def handleDiscovery(context, event):
    logger.info('handleDiscovery')
    payload = ''
    header = {
        "namespace": "Alexa.ConnectedHome.Discovery",
        "name": "DiscoverAppliancesResponse",
        "payloadVersion": "2"
        }

    if event['header']['name'] == 'DiscoverAppliancesRequest':
        devices = urllib2.urlopen("https://"+myips+".ipmagic.de/hook/alexa/discover").read()
        
        payload = { "discoveredAppliances":
            json.loads(devices)            
            }
            
    return { 'header': header, 'payload': payload }

def handleControl(context, event):
    payload = {}
    device_id = event['payload']['appliance']['applianceId']
    message_id = event['header']['messageId']
    request_type = event['header']['name']
    
    logger.info(event['header']['name'] + ": " + device_id)
    logger.info(event)
    confirmation = ''

    if request_type == 'TurnOnRequest':
        #payload = { }
        value = 100;
        confirmation = 'TurnOnConfirmation'
    elif request_type == 'TurnOffRequest':
        #payload = { }
        value = 0;
        confirmation = 'TurnOffConfirmation'
    elif request_type == 'SetPercentageRequest':
        value = event['payload']['percentageState']['value']
        logger.info('SetPercentageRequest ' + str(value))
        confirmation = 'SetPercentageConfirmation'
    elif request_type == 'SetTargetTemperatureRequest':
        value = event['payload']['targetTemperature']['value']
        payload = {"targetTemperature":{
            "value":value}
            }
        logger.info('SetTargetTemperatureRequest ' + str(value))
        confirmation = 'SetTargetTemperatureConfirmation'
        
    urllib2.urlopen("https://"+myips+".ipmagic.de/hook/alexa/control/dimm?id="+device_id+"&value="+str(value)).read()    
        
    logger.info('-->' + confirmation)

    header = {
        "namespace":"Alexa.ConnectedHome.Control",
        "name":confirmation,
        "payloadVersion":"2",
        "messageId": message_id
        }
    return { 'header': header, 'payload': payload }  