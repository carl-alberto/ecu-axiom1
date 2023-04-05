<?php

namespace NF_FU_VENDOR;

// This file was auto-generated from sdk-root/src/data/connectparticipant/2018-09-07/api-2.json
return ['version' => '2.0', 'metadata' => ['apiVersion' => '2018-09-07', 'endpointPrefix' => 'participant.connect', 'jsonVersion' => '1.1', 'protocol' => 'rest-json', 'serviceAbbreviation' => 'Amazon Connect Participant', 'serviceFullName' => 'Amazon Connect Participant Service', 'serviceId' => 'ConnectParticipant', 'signatureVersion' => 'v4', 'signingName' => 'execute-api', 'uid' => 'connectparticipant-2018-09-07'], 'operations' => ['CreateParticipantConnection' => ['name' => 'CreateParticipantConnection', 'http' => ['method' => 'POST', 'requestUri' => '/participant/connection'], 'input' => ['shape' => 'CreateParticipantConnectionRequest'], 'output' => ['shape' => 'CreateParticipantConnectionResponse'], 'errors' => [['shape' => 'AccessDeniedException'], ['shape' => 'InternalServerException'], ['shape' => 'ThrottlingException'], ['shape' => 'ValidationException']]], 'DisconnectParticipant' => ['name' => 'DisconnectParticipant', 'http' => ['method' => 'POST', 'requestUri' => '/participant/disconnect'], 'input' => ['shape' => 'DisconnectParticipantRequest'], 'output' => ['shape' => 'DisconnectParticipantResponse'], 'errors' => [['shape' => 'AccessDeniedException'], ['shape' => 'InternalServerException'], ['shape' => 'ThrottlingException'], ['shape' => 'ValidationException']]], 'GetTranscript' => ['name' => 'GetTranscript', 'http' => ['method' => 'POST', 'requestUri' => '/participant/transcript'], 'input' => ['shape' => 'GetTranscriptRequest'], 'output' => ['shape' => 'GetTranscriptResponse'], 'errors' => [['shape' => 'AccessDeniedException'], ['shape' => 'InternalServerException'], ['shape' => 'ThrottlingException'], ['shape' => 'ValidationException']]], 'SendEvent' => ['name' => 'SendEvent', 'http' => ['method' => 'POST', 'requestUri' => '/participant/event'], 'input' => ['shape' => 'SendEventRequest'], 'output' => ['shape' => 'SendEventResponse'], 'errors' => [['shape' => 'AccessDeniedException'], ['shape' => 'InternalServerException'], ['shape' => 'ThrottlingException'], ['shape' => 'ValidationException']]], 'SendMessage' => ['name' => 'SendMessage', 'http' => ['method' => 'POST', 'requestUri' => '/participant/message'], 'input' => ['shape' => 'SendMessageRequest'], 'output' => ['shape' => 'SendMessageResponse'], 'errors' => [['shape' => 'AccessDeniedException'], ['shape' => 'InternalServerException'], ['shape' => 'ThrottlingException'], ['shape' => 'ValidationException']]]], 'shapes' => ['AccessDeniedException' => ['type' => 'structure', 'required' => ['Message'], 'members' => ['Message' => ['shape' => 'Message']], 'error' => ['httpStatusCode' => 403], 'exception' => \true], 'ChatContent' => ['type' => 'string', 'max' => 1024, 'min' => 1], 'ChatContentType' => ['type' => 'string', 'max' => 100, 'min' => 1], 'ChatItemId' => ['type' => 'string', 'max' => 256, 'min' => 1], 'ChatItemType' => ['type' => 'string', 'enum' => ['MESSAGE', 'EVENT', 'CONNECTION_ACK']], 'ClientToken' => ['type' => 'string', 'max' => 500], 'ConnectionCredentials' => ['type' => 'structure', 'members' => ['ConnectionToken' => ['shape' => 'ParticipantToken'], 'Expiry' => ['shape' => 'ISO8601Datetime']]], 'ConnectionType' => ['type' => 'string', 'enum' => ['WEBSOCKET', 'CONNECTION_CREDENTIALS']], 'ConnectionTypeList' => ['type' => 'list', 'member' => ['shape' => 'ConnectionType'], 'min' => 1], 'ContactId' => ['type' => 'string', 'max' => 256, 'min' => 1], 'CreateParticipantConnectionRequest' => ['type' => 'structure', 'required' => ['Type', 'ParticipantToken'], 'members' => ['Type' => ['shape' => 'ConnectionTypeList'], 'ParticipantToken' => ['shape' => 'ParticipantToken', 'location' => 'header', 'locationName' => 'X-Amz-Bearer']]], 'CreateParticipantConnectionResponse' => ['type' => 'structure', 'members' => ['Websocket' => ['shape' => 'Websocket'], 'ConnectionCredentials' => ['shape' => 'ConnectionCredentials']]], 'DisconnectParticipantRequest' => ['type' => 'structure', 'required' => ['ConnectionToken'], 'members' => ['ClientToken' => ['shape' => 'ClientToken', 'idempotencyToken' => \true], 'ConnectionToken' => ['shape' => 'ParticipantToken', 'location' => 'header', 'locationName' => 'X-Amz-Bearer']]], 'DisconnectParticipantResponse' => ['type' => 'structure', 'members' => []], 'DisplayName' => ['type' => 'string', 'max' => 256, 'min' => 1], 'GetTranscriptRequest' => ['type' => 'structure', 'required' => ['ConnectionToken'], 'members' => ['ContactId' => ['shape' => 'ContactId'], 'MaxResults' => ['shape' => 'MaxResults', 'box' => \true], 'NextToken' => ['shape' => 'NextToken'], 'ScanDirection' => ['shape' => 'ScanDirection'], 'SortOrder' => ['shape' => 'SortKey'], 'StartPosition' => ['shape' => 'StartPosition'], 'ConnectionToken' => ['shape' => 'ParticipantToken', 'location' => 'header', 'locationName' => 'X-Amz-Bearer']]], 'GetTranscriptResponse' => ['type' => 'structure', 'members' => ['InitialContactId' => ['shape' => 'ContactId'], 'Transcript' => ['shape' => 'Transcript'], 'NextToken' => ['shape' => 'NextToken']]], 'ISO8601Datetime' => ['type' => 'string'], 'Instant' => ['type' => 'string', 'max' => 100, 'min' => 1], 'InternalServerException' => ['type' => 'structure', 'required' => ['Message'], 'members' => ['Message' => ['shape' => 'Message']], 'error' => ['httpStatusCode' => 500], 'exception' => \true, 'fault' => \true], 'Item' => ['type' => 'structure', 'members' => ['AbsoluteTime' => ['shape' => 'Instant'], 'Content' => ['shape' => 'ChatContent'], 'ContentType' => ['shape' => 'ChatContentType'], 'Id' => ['shape' => 'ChatItemId'], 'Type' => ['shape' => 'ChatItemType'], 'ParticipantId' => ['shape' => 'ParticipantId'], 'DisplayName' => ['shape' => 'DisplayName'], 'ParticipantRole' => ['shape' => 'ParticipantRole']]], 'MaxResults' => ['type' => 'integer', 'max' => 100, 'min' => 0], 'Message' => ['type' => 'string'], 'MostRecent' => ['type' => 'integer', 'max' => 100, 'min' => 0], 'NextToken' => ['type' => 'string', 'max' => 1000, 'min' => 1], 'ParticipantId' => ['type' => 'string', 'max' => 256, 'min' => 1], 'ParticipantRole' => ['type' => 'string', 'enum' => ['AGENT', 'CUSTOMER', 'SYSTEM']], 'ParticipantToken' => ['type' => 'string', 'max' => 1000, 'min' => 1], 'PreSignedConnectionUrl' => ['type' => 'string', 'max' => 2000, 'min' => 1], 'Reason' => ['type' => 'string', 'max' => 2000, 'min' => 1], 'ScanDirection' => ['type' => 'string', 'enum' => ['FORWARD', 'BACKWARD']], 'SendEventRequest' => ['type' => 'structure', 'required' => ['ContentType', 'ConnectionToken'], 'members' => ['ContentType' => ['shape' => 'ChatContentType'], 'Content' => ['shape' => 'ChatContent'], 'ClientToken' => ['shape' => 'ClientToken', 'idempotencyToken' => \true], 'ConnectionToken' => ['shape' => 'ParticipantToken', 'location' => 'header', 'locationName' => 'X-Amz-Bearer']]], 'SendEventResponse' => ['type' => 'structure', 'members' => ['Id' => ['shape' => 'ChatItemId'], 'AbsoluteTime' => ['shape' => 'Instant']]], 'SendMessageRequest' => ['type' => 'structure', 'required' => ['ContentType', 'Content', 'ConnectionToken'], 'members' => ['ContentType' => ['shape' => 'ChatContentType'], 'Content' => ['shape' => 'ChatContent'], 'ClientToken' => ['shape' => 'ClientToken', 'idempotencyToken' => \true], 'ConnectionToken' => ['shape' => 'ParticipantToken', 'location' => 'header', 'locationName' => 'X-Amz-Bearer']]], 'SendMessageResponse' => ['type' => 'structure', 'members' => ['Id' => ['shape' => 'ChatItemId'], 'AbsoluteTime' => ['shape' => 'Instant']]], 'SortKey' => ['type' => 'string', 'enum' => ['DESCENDING', 'ASCENDING']], 'StartPosition' => ['type' => 'structure', 'members' => ['Id' => ['shape' => 'ChatItemId'], 'AbsoluteTime' => ['shape' => 'Instant'], 'MostRecent' => ['shape' => 'MostRecent']]], 'ThrottlingException' => ['type' => 'structure', 'required' => ['Message'], 'members' => ['Message' => ['shape' => 'Message']], 'error' => ['httpStatusCode' => 429], 'exception' => \true], 'Transcript' => ['type' => 'list', 'member' => ['shape' => 'Item']], 'ValidationException' => ['type' => 'structure', 'required' => ['Message'], 'members' => ['Message' => ['shape' => 'Reason']], 'error' => ['httpStatusCode' => 400], 'exception' => \true], 'Websocket' => ['type' => 'structure', 'members' => ['Url' => ['shape' => 'PreSignedConnectionUrl'], 'ConnectionExpiry' => ['shape' => 'ISO8601Datetime']]]]];
