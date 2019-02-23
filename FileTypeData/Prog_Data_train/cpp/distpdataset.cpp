//
//  distdataset.cpp
//  Mothur
//
//  Created by Sarah Westcott on 6/6/16.
//  Copyright (c) 2016 Schloss Lab. All rights reserved.
//

#include "distpdataset.h"

/***********************************************************************/
DistPDataSet::DistPDataSet() {
    m = MothurOut::getInstance();
    phylipFile = m->getTestFilePath() + "stability.MISeq_SOP.trim.contigs.good.unique.good.filter.unique.precluster.pick.pick.pick.phylip.dist";
}
/***********************************************************************/
