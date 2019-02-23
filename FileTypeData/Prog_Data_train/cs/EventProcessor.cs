using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Xml.Linq;
using Akka.Actor;
using Microsoft.ServiceBus.Messaging;
using MusicIndexer.Actors;
using MusicIndexer.Messages;
using Serilog;

namespace MusicIndexer.Processors
{
    public class EventProcessor : IEventProcessor
    {
        private readonly IActorRef recordCreator;
        private readonly ActorSystem system;

        public EventProcessor()
        {
            system = ActorSystem.Create("MusicFileProcessingSystem");
            var resourceDownloaderProps = Props.Create<ResourceDownloader>();
            var resourceDownloader = system.ActorOf(resourceDownloaderProps, "resourceDownloader");
            var resourceStorerProps = Props.Create<BlobStorageActor>();
            var resourceStorer = system.ActorOf(resourceStorerProps, "resourceStorer");
            var recordCreatorProps = Props.Create<Mp3RecordManager>(resourceDownloader, resourceStorer);
            recordCreator = system.ActorOf(recordCreatorProps, "recordCreator");
        }

        public async Task ProcessEventsAsync(PartitionContext context, IEnumerable<EventData> messages)
        {
            foreach (var eventData in messages)
            {
                Log.Logger.Information("Processing event: {sequenceNumber}", eventData.SequenceNumber);
                var data = Encoding.UTF8.GetString(eventData.GetBytes());
                try
                {
                    if (data.Contains("<"))
                    {
                        var eventXml = XElement.Parse(data);
                        var lastChangeNode =
                            eventXml.Descendants(XName.Get("LastChange"))
                                .SingleOrDefault();
                        if (lastChangeNode != null)
                        {
                            var rawEvent = XElement.Parse(lastChangeNode.Value);
                            var currentTrackMetaDataNode = rawEvent
                                .Descendants(XName.Get("CurrentTrackMetaData",
                                    "urn:schemas-upnp-org:metadata-1-0/AVT/"))
                                .SingleOrDefault(n => !String.IsNullOrEmpty(n.Attribute("val").Value));
                            if (currentTrackMetaDataNode != null)
                            {
                                var metadata = currentTrackMetaDataNode.Attribute(XName.Get("val")).Value;
                                var metaXml = XElement.Parse(metadata);
                                var classTypeNode =
                                    metaXml.Descendants(XName.Get("class", "urn:schemas-upnp-org:metadata-1-0/upnp/"))
                                        .SingleOrDefault();
                                if (classTypeNode != null)
                                {
                                    var classType = classTypeNode.Value;
                                    switch (classType)
                                    {
                                        case "object.item.audioItem.musicTrack":
                                            var res =
                                                metaXml.Descendants(XName.Get("res",
                                                    "urn:schemas-upnp-org:metadata-1-0/DIDL-Lite/"))
                                                    .SingleOrDefault();
                                            if (res != null)
                                            {
                                                var urlString = res.Value;
                                                var albumNode =
                                                    metaXml.Descendants(XName.Get("album",
                                                        "urn:schemas-upnp-org:metadata-1-0/upnp/"))
                                                        .SingleOrDefault();
                                                string album = null;
                                                if (albumNode != null)
                                                    album = albumNode.Value;
                                                var track =
                                                    metaXml.Descendants(XName.Get("title",
                                                        "http://purl.org/dc/elements/1.1/"))
                                                        .Single()
                                                        .Value;
                                                var artist =
                                                    metaXml.Descendants(XName.Get("creator",
                                                        "http://purl.org/dc/elements/1.1/"))
                                                        .Single()
                                                        .Value;
                                                urlString = urlString.Replace("pndrradio-", string.Empty);
                                                var fileLocation = new Uri(urlString);
                                                var albumArtUriNode = metaXml.Descendants(XName.Get("albumArtURI",
                                                    "urn:schemas-upnp-org:metadata-1-0/upnp/")).SingleOrDefault();
                                                Uri albumArtUri = null;
                                                if (albumArtUriNode != null)
                                                    albumArtUri = new Uri(albumArtUriNode.Value);
                                                recordCreator.Tell(new NewRecordMessage(artist, album, track,
                                                    fileLocation, albumArtUri));
                                            }
                                            break;
                                    }
                                }
                            }
                        }
                    }
                }
                catch (Exception e)
                {
                    Log.Logger.Error(e, "Error: {e}");
                }
                await context.CheckpointAsync(eventData);
            }
        }


        public async Task OpenAsync(PartitionContext context)
        {
            Log.Information("SimpleEventProcessor initialize.  Partition: '{partitionId}', Offset: '{offset}'",
                context.Lease.PartitionId, context.Lease.Offset);
        }

        public async Task CloseAsync(PartitionContext context, CloseReason reason)
        {
            Log.Information("Processor Shuting Down. Partition '{partitionId}', Reason: '{reason}'.",
                context.Lease.PartitionId, reason);

            if (reason == CloseReason.Shutdown)
                await context.CheckpointAsync();
            system.AwaitTermination();
        }
    }
}