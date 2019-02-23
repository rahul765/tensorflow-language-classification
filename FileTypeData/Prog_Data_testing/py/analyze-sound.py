#!/usr/bin/python
## This is an example of a simple sound capture script.
##
## The script opens an ALSA pcm for sound capture. Set
## various attributes of the capture, and reads in a loop,
## Then prints the volume.
##
## To test it out, run it and shout at your microphone:

import alsaaudio, time, audioop
import numpy.fft

# Open the device in nonblocking capture mode. The last argument could
# just as well have been zero for blocking mode. Then we could have
# left out the sleep call in the bottom of the loop
inp = alsaaudio.PCM(alsaaudio.PCM_CAPTURE, alsaaudio.PCM_NONBLOCK)

# Set attributes: Mono, 8000 Hz, 16 bit little endian samples
inp.setchannels(1)
inp.setrate(8000)
inp.setformat(alsaaudio.PCM_FORMAT_S16_LE)

# The period size controls the internal number of frames per period.
# The significance of this parameter is documented in the ALSA api.
# For our purposes, it is suficcient to know that reads from the device
# will return this many frames. Each frame being 2 bytes long.
# This means that the reads below will return either 320 bytes of data
# or 0 bytes of data. The latter is possible because we are in nonblocking
# mode.
inp.setperiodsize(1020)

def cluster(data):
    for i in range(0, len(data), 2):
        yield ord(data[i]) + (ord(data[i+1]) << 8)

def peak_frequency(samples, sample_rate):
    """ returns (peak_freq, amplitude) """
    np_samples = numpy.array(samples)
    spectrum = numpy.fft.rfft(np_samples)
    frequencies = sample_rate * numpy.fft.fftfreq(np_samples.shape[-1])
    indexbounds = getrange(frequencies, 1000, 1500)
    # print abs(sum(spectrum.real[indexbounds[0]:indexbounds[1]])/((indexbounds[1]-indexbounds[0])*sample_rate))
    return max(
        zip(list(frequencies), list(spectrum.real)), key = lambda x: abs(x[1])
        )

def getrange(A, low, high):
    L = None
    H = None
    for i in range(len(A)):
        if (L is None) and A[i] > low:
            L = i
        if (H is None) and (A[i] > high):
            H = i
            break;
    if L is None or H is None:
        raise ValueException
    return (L, H)

while True:
    # Read data from device
    l, data = inp.read()
    if len(data) != 0:
        print len(data)
    if l:
        c = cluster(data)
        # Sampled at 8000 / sec, but only sampled 160 samples
        i = list(c)
        print len(i)
        peak_freq, ampl = peak_frequency(i, 8000)
        # print peak_freq, ampl
    time.sleep(.001)
