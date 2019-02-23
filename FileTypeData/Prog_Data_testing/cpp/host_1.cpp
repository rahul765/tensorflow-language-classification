#define __CL_ENABLE_EXCEPTIONS
#include "cl1.hpp" //cl.hpp mock, just move the normal cl.hpp to cl1.hpp
                  //and put it in . CL/

#include <iostream>
#include <vector>
#include <fstream>
#include <string>

using namespace std;

//const uint32_t LENGTH = 2147483648; /*maximum reached on the CPU ~25GB*/
//const uint32_t LENGTH = 268435456;
const uint32_t LENGTH = 134217728;
//const uint32_t OPS_PER_WI = 4096;
//const float GOPS = (float)OPS_PER_WI*LENGTH/1e9f;

#ifdef __INT__
    #define TYPE int
#elif __FLOAT__
    #define TYPE float
#else
    #define TYPE float
#endif

float timeInUS(cl::Event &timeEvent){
    cl_ulong start = timeEvent.getProfilingInfo<CL_PROFILING_COMMAND_START>() / 1000;
    cl_ulong end = timeEvent.getProfilingInfo<CL_PROFILING_COMMAND_END>() / 1000;

    return (float)((int)end - (int)start);
}

int ocl_read_binary(const char *filename, char* &buffer)
{
    try
    {
        std::ifstream is;
        is.open (filename, std::ios::binary );
        is.seekg (0, std::ios::end);
        int length = is.tellg();
        is.seekg (0, std::ios::beg);
        buffer = new char [length]; //awful should use unique_ptr...this could leak
        is.read (buffer, length);
        is.close();
        return length;
    }
    catch(...) { std::cout << "Binary read function failure" << std::endl; }
    return 0;
}

int main(int argc, char** argv){
    if (argc < 3){ 
        cout<< argv[0] << " <kernel_file>" << " <WGsize>" << endl;
        return -1;
    }

    const string kernelFile = argv[1];
    cout << "kernel file: " << kernelFile << "\n";
    const uint32_t wgSize = atoi(argv[2]);
    cout << "WGsize " << wgSize << endl;

    uint32_t elementsPerWi = 0;
    if(argc == 4) elementsPerWi = atoi(argv[3]);
    else elementsPerWi = 1;

    TYPE* a = new TYPE[LENGTH]{(TYPE)0.0f};
    TYPE* b = new TYPE[LENGTH]{(TYPE)0.0f};
    TYPE* c = new TYPE[LENGTH]{(TYPE)0.0f};

    for(uint32_t i=0; i<LENGTH; ++i) {
        //a[i] = (TYPE)0.1+i/100000000;
        //b[i] = (TYPE)(i/100000000+0.1);
        a[i] = 4;  
        b[i] = 6;  
    }

    cl_int err = CL_SUCCESS;

    try{
        //get available platforms
        vector<cl::Platform> platforms;
        cl::Platform::get(&platforms);
        cout<<"Number of platforms: "<<platforms.size()<<"\n";

        //list of devices
        cl_context_properties properties[] = {CL_CONTEXT_PLATFORM, (cl_context_properties)(platforms[0])(), 0};
#ifdef __CPU__
        cl::Context context(CL_DEVICE_TYPE_CPU, properties);
#elif __PHI__
        cl::Context context(CL_DEVICE_TYPE_ACCELERATOR, properties);
#elif __GPU__
        cl::Context context(CL_DEVICE_TYPE_GPU, properties);
#elif __FPGA__
        cl::Context context(CL_DEVICE_TYPE_ACCELERATOR, properties);
#else
        cl::Context context(CL_DEVICE_TYPE_CPU, properties);
#endif
        vector<cl::Device> devices = context.getInfo<CL_CONTEXT_DEVICES>();
        cout<<"Number of devices: "<<devices.size()<<"\n";

        //create command queue for first device
        cl::CommandQueue queue(context, devices[0], CL_QUEUE_PROFILING_ENABLE, &err);

        //read kernel file
#ifdef __FPGA__
        char* bin;
        int bin_length = ocl_read_binary(kernelFile.c_str(), bin);
        cl::Program::Binaries binaries(1, make_pair(bin, bin_length));
        cl::Program program(context, devices, binaries);
#else
        ifstream sourceFile(kernelFile);
        string sourceCode(istreambuf_iterator<char>(sourceFile), (istreambuf_iterator<char>()));
        cl::Program::Sources source(1, make_pair(sourceCode.c_str(), sourceCode.length()+1));
        cl::Program program(context, source);
#endif

        //compilation string for the device
#ifdef __INT__
        const string deviceLine = "-D__INT__";
#elif __FLOAT__
        const string deviceLine = "-D__FLOAT__";
#else
        const string deviceLine =  "-D__FLOAT__";
#endif

#ifdef __NO_VEC__
        const string vectorisationLine = " -D__NO_VEC__";
#else
        const string vectorisationLine = "";
#endif
        const string userLine = " -DELEMENTS_PER_WI="+to_string(elementsPerWi);
        const string compilationLine = deviceLine + vectorisationLine + userLine;

        cout << "Kernel compilation line: " << compilationLine << "\n\n";

        //build program for these specific devices
        program.build(devices, compilationLine.c_str());
        //make kernel
        cl::Kernel kernel(program, "add");

        //create device memory buffers
        cl::Buffer d_a = cl::Buffer(context, CL_MEM_READ_ONLY, LENGTH*sizeof(TYPE));
        cl::Buffer d_b = cl::Buffer(context, CL_MEM_READ_ONLY, LENGTH*sizeof(TYPE));
        cl::Buffer d_c = cl::Buffer(context, CL_MEM_WRITE_ONLY, LENGTH*sizeof(TYPE)); 

        //copy data to the device
        queue.enqueueWriteBuffer(d_a, CL_TRUE, 0, LENGTH*sizeof(TYPE), a);
        queue.enqueueWriteBuffer(d_b, CL_TRUE, 0, LENGTH*sizeof(TYPE), b);

        //set kernel arguments
        kernel.setArg(0, d_a);
        kernel.setArg(1, d_b);
        kernel.setArg(2, d_c);

        //run the kernel on specific ND range
#ifdef __FPGA__
        cl::NDRange global(1);
        cl::NDRange local(1);
#else
        cl::NDRange global(LENGTH/elementsPerWi);
        cl::NDRange local(wgSize);
#endif

        float timeMS = 0.0f;
        for(uint32_t i=0; i<10; ++i){
            cl::Event timeEvent;

            queue.enqueueNDRangeKernel(kernel, cl::NullRange, global, local, NULL, &timeEvent);
            queue.finish();

            timeMS += timeInUS(timeEvent)/1e3f;
        }

        timeMS = timeMS/10.0f;
        cout << "Time[ms]: " << timeMS << "\n";
        float timeS = timeMS/1e3f;
        //cout << "Ops: " <<  (long)OPS_PER_WI*LENGTH <<"\n";

#ifdef __INT__
        //cout << "IOPS: " << GOPS/timeS <<"\n";
#elif __FLOAT__
        //cout << "FLOPS: " << GOPS/timeS <<"\n";
#else
        //cout << "FLOPS: " << GOPS/timeS <<"\n";
#endif
        //read buffer C into a local list
        queue.enqueueReadBuffer(d_c, CL_TRUE, 0, LENGTH*sizeof(TYPE), c); 
#ifdef __PRINT_RESULTS__
        for(uint32_t i=0; i<LENGTH; ++i) cout<<c[i]<<"\n"; 
#endif
        
        bool success = true;
        for(uint32_t i=0; i<LENGTH; ++i){
            if(c[i] != 10){
                cout << "ERROR element " << i << endl; 
                success = false;
                break;
            }
        } 
        if(success) cout << "Results verified......OK" << endl;

#ifdef __FPGA__
        delete [] bin;
#endif
    }
    catch(cl::Error& error) {
       cout<<error.what()<<"("<<error.err()<<")"<<"\n";
    }

    return 0;
}
