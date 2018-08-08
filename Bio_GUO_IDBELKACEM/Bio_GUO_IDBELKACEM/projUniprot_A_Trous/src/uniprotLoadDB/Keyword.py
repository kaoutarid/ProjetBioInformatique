# coding: utf8
'''
Created on 27 janv. 2018
Classe Keyword : mots cl�s de la base uniprotLoadDB
   Attributs :
      - kwId : keyword id
      - kwLabel : keyword label
@author: Sarah Cohen Boulakia
'''
class Keyword:

    # Parametre de classe utilise pour dire si les Keyword doivent etre inseres 
    # en base quand insertDB est appele
    DEBUG_INSERT_DB = True
    
    def __init__(self, kwId, kwLabel):
        self._kwId = kwId
        self._kwLabel = kwLabel
    
    
    '''
    Si le mot cle n'existe pas, ajout en base.
    @param curDb: Curseur sur la base de donnees oracle 
    @return N/A
    '''    
    def insertDB (self, curDB):
        curDB.prepare ("SELECT kw_id " \
                                + " FROM keywords " \
                                + " WHERE kw_id=:kwId")
        curDB.execute (None, {'kwId': self._kwId })
        raw = curDB.fetchone ()
        if raw == None:
            if Keyword.DEBUG_INSERT_DB:
                #### TODO : Insérer le keyword s'il n'existe pas
                #### Cf. exemple de la classe Gene si besoin
                ####          ####
                #### FIN TODO ####
                curDB.prepare ("INSERT into keywords(kw_id, kw_label) " \
                                + "values(:kwID,:kwLabel)")
                curDB.execute (None, {'kwId': self._kwId, 'kwLabel': self._kwLabel })
                return self._kwId
        else:
            return raw[0]
                

